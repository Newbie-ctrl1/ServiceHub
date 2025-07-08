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
            // Clean message data before saving
            $cleanMessage = $this->cleanUtf8($this->messageData['message'] ?? null);
            $cleanImageUrl = $this->cleanUtf8($this->messageData['image_url'] ?? null);
            
            // Create message in database
            $message = Message::create([
                'user_id' => $this->senderId,
                'receiver_id' => $this->receiverId,
                'message' => $cleanMessage,
                'image_url' => $cleanImageUrl,
                'is_read' => false
            ]);

            // Load sender relationship
            $message->load('sender');
            
            // Validate that we can broadcast this data safely
            $testData = [
                'message_id' => $message->id,
                'message_text' => $message->message,
                'sender_name' => $message->sender->name,
                'sender_id' => $message->sender->id
            ];
            
            $jsonTest = json_encode($testData);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('Message contains problematic data, broadcasting with cleaned data', [
                    'message_id' => $message->id,
                    'json_error' => json_last_error_msg()
                ]);
            }

            // Broadcast the message with error handling
            try {
                broadcast(new MessageSent($message, $message->sender));
                Log::info('Chat message broadcasted successfully', [
                    'message_id' => $message->id,
                    'sender_id' => $this->senderId,
                    'receiver_id' => $this->receiverId
                ]);
            } catch (\Exception $broadcastError) {
                Log::error('Failed to broadcast message, but message was saved', [
                    'message_id' => $message->id,
                    'broadcast_error' => $broadcastError->getMessage(),
                    'sender_id' => $this->senderId,
                    'receiver_id' => $this->receiverId
                ]);
                // Don't throw here - message was saved successfully
            }

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
     * Clean UTF-8 data
     */
    private function cleanUtf8($data)
    {
        if ($data === null || $data === '') {
            return $data;
        }
        
        // Convert to UTF-8 and remove problematic characters
        $cleaned = mb_convert_encoding($data, 'UTF-8', 'UTF-8');
        $cleaned = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $cleaned);
        
        // Validate UTF-8 encoding
        if (!mb_check_encoding($cleaned, 'UTF-8')) {
            return '[Content contains invalid characters]';
        }
        
        return $cleaned;
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
