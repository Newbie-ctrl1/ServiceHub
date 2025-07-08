<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Services\ProfilePhotoService;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'default';

    public $message;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message, User $user)
    {
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->message->receiver_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        try {
            // Clean and validate all data before broadcasting
            $messageText = ProfilePhotoService::cleanForBroadcast($this->message->message);
            $imageUrl = ProfilePhotoService::cleanForBroadcast($this->message->image_url);
            $userName = ProfilePhotoService::cleanForBroadcast($this->user->name);
            
            // Handle profile photo safely - convert binary to base64 if needed
            $profilePhoto = null;
            if ($this->user->profile_photo) {
                // If it's binary data, convert to base64 data URL
                if ($this->isBinaryData($this->user->profile_photo)) {
                    $profilePhoto = 'data:image/jpeg;base64,' . base64_encode($this->user->profile_photo);
                } else {
                    $profilePhoto = ProfilePhotoService::cleanForBroadcast($this->user->profile_photo);
                }
            }
            
            $broadcastData = [
                'message' => [
                    'id' => (int) $this->message->id,
                    'user_id' => (int) $this->message->user_id,
                    'receiver_id' => (int) $this->message->receiver_id,
                    'message' => $messageText,
                    'image_url' => $imageUrl,
                    'created_at' => $this->message->created_at->format('Y-m-d H:i:s'),
                    'sender' => [
                        'id' => (int) $this->user->id,
                        'name' => $userName,
                        'profile_photo' => $this->user->safe_profile_photo
                    ]
                ]
            ];
            
            // Validate JSON encoding before broadcasting
            $jsonTest = json_encode($broadcastData);
            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::error('MessageSent broadcast encoding error: ' . json_last_error_msg());
                // Return safe fallback data
                return [
                    'message' => [
                        'id' => (int) $this->message->id,
                        'user_id' => (int) $this->message->user_id,
                        'receiver_id' => (int) $this->message->receiver_id,
                        'message' => '[Message content could not be encoded]',
                        'image_url' => null,
                        'created_at' => $this->message->created_at->format('Y-m-d H:i:s'),
                        'sender' => [
                            'id' => (int) $this->user->id,
                            'name' => '[User name could not be encoded]',
                            'profile_photo' => null
                        ]
                    ]
                ];
            }
            
            return $broadcastData;
            
        } catch (\Exception $e) {
            \Log::error('MessageSent broadcast error: ' . $e->getMessage());
            // Return minimal safe data
            return [
                'message' => [
                    'id' => (int) $this->message->id,
                    'user_id' => (int) $this->message->user_id,
                    'receiver_id' => (int) $this->message->receiver_id,
                    'message' => 'Message could not be processed',
                    'image_url' => null,
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'sender' => [
                        'id' => (int) $this->user->id,
                        'name' => 'User',
                        'profile_photo' => null
                    ]
                ]
            ];
        }
    }
    

}