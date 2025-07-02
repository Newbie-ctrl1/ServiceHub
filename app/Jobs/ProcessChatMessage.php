<?php

namespace App\Jobs;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessChatMessage implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public $timeout = 60;
    public $tries = 3;

    protected $messageData;
    protected $senderId;
    protected $receiverId;

    /**
     * Create a new job instance.
     */
    public function __construct(array $messageData, int $senderId, int $receiverId)
    {
        $this->messageData = $messageData;
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Create message in database
            $message = Message::create([
                'user_id' => $this->senderId,
                'receiver_id' => $this->receiverId,
                'message' => $this->messageData['message'] ?? null,
                'image_url' => $this->messageData['image_url'] ?? null,
                'is_read' => false
            ]);

            // Load sender relationship
            $message->load('sender');

            // Broadcast the message
            broadcast(new MessageSent($message, $message->sender));

            Log::info('Chat message processed successfully', [
                'message_id' => $message->id,
                'sender_id' => $this->senderId,
                'receiver_id' => $this->receiverId
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to process chat message', [
                'error' => $e->getMessage(),
                'sender_id' => $this->senderId,
                'receiver_id' => $this->receiverId,
                'message_data' => $this->messageData
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessChatMessage job failed', [
            'error' => $exception->getMessage(),
            'sender_id' => $this->senderId,
            'receiver_id' => $this->receiverId,
            'message_data' => $this->messageData
        ]);
    }
}
