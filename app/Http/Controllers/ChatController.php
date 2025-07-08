<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Jobs\ProcessChatMessage;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function index($userId = null)
    {
        // Get all users except current user for contact list
        $users = User::where('id', '!=', Auth::id())->get();
        
        // If userId is provided, get the selected user info
        $selectedUser = null;
        if ($userId) {
            $selectedUser = User::find($userId);
        }
        
        return view('Chat.index', compact('users', 'selectedUser'));
    }
    
    /**
     * Get messages between current user and another user
     */
    public function getMessages(Request $request, $userId)
    {
        try {
            $messages = Message::betweenUsers(Auth::id(), $userId)
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'asc')
                ->get();
                
            // Transform messages with aggressive UTF-8 cleaning
            $transformedMessages = $messages->map(function ($message) {
                try {
                    // Aggressive cleaning for message content
                    $cleanMessage = $this->aggressiveUtf8Clean($message->message);
                    
                    // Aggressive cleaning for user name
                    $cleanUserName = $this->aggressiveUtf8Clean($message->sender->name ?? '');
                    
                    // Clean profile photo path
                    $cleanProfilePhoto = $this->aggressiveUtf8Clean($message->sender->profile_photo ?? '');
                    
                    // Clean image URL
                    $cleanImageUrl = $this->aggressiveUtf8Clean($message->image_url ?? '');
                    
                    $messageData = [
                        'id' => (int) $message->id,
                        'user_id' => (int) $message->user_id,
                        'receiver_id' => (int) $message->receiver_id,
                        'message' => $cleanMessage,
                        'image_url' => $cleanImageUrl,
                        'is_read' => (bool) $message->is_read,
                        'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                        'user' => [
                            'id' => (int) $message->sender->id,
                            'name' => $cleanUserName,
                            'profile_photo' => $message->sender->safe_profile_photo
                        ]
                    ];
                    
                    // Test individual message JSON encoding
                    $testJson = json_encode($messageData);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        \Log::warning('Skipping corrupted message ID: ' . $message->id . ' - ' . json_last_error_msg());
                        return null; // Skip this message
                    }
                    
                    return $messageData;
                } catch (\Exception $e) {
                    \Log::warning('Error processing message ID: ' . $message->id . ' - ' . $e->getMessage());
                    return null; // Skip this message
                }
            })->filter(); // Remove null values
                
            // Mark messages as read
            Message::where('user_id', $userId)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
                
            // Validate JSON encoding before returning
            $jsonData = [
                'success' => true,
                'messages' => $transformedMessages
            ];
            
            // Test JSON encoding
            $jsonTest = json_encode($jsonData);
            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::error('JSON encoding error: ' . json_last_error_msg());
                return response()->json([
                    'success' => false,
                    'error' => 'Data encoding error',
                    'messages' => []
                ], 500);
            }
            
            return response()->json($jsonData);
        } catch (\Exception $e) {
            \Log::error('Error loading messages: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'error' => 'Failed to load messages',
                'messages' => []
            ], 500);
        }
    }
    
    /**
     * Aggressive UTF-8 cleaning with multiple fallback strategies
     */
    private function aggressiveUtf8Clean($input)
    {
        if ($input === null || $input === '') {
            return $input;
        }
        
        try {
            // Strategy 1: Standard UTF-8 conversion
            $cleaned = mb_convert_encoding($input, 'UTF-8', 'UTF-8');
            
            // Strategy 2: Remove control characters
            $cleaned = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $cleaned);
            
            // Strategy 3: Remove non-UTF-8 sequences
            $cleaned = preg_replace('/[\x80-\xFF]+/', '', $cleaned);
            
            // Strategy 4: Force UTF-8 encoding from multiple sources
            if (!mb_check_encoding($cleaned, 'UTF-8')) {
                // Try different source encodings
                $encodings = ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'ASCII'];
                foreach ($encodings as $encoding) {
                    try {
                        $test = mb_convert_encoding($input, 'UTF-8', $encoding);
                        if (mb_check_encoding($test, 'UTF-8')) {
                            $cleaned = $test;
                            break;
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
            
            // Strategy 5: Final fallback - keep only ASCII characters
            if (!mb_check_encoding($cleaned, 'UTF-8')) {
                $cleaned = preg_replace('/[^\x20-\x7E]/', '', $input);
            }
            
            // Strategy 6: Ultimate fallback - return safe placeholder
            if (!mb_check_encoding($cleaned, 'UTF-8')) {
                return '[Content contains invalid characters]';
            }
            
            return $cleaned;
            
        } catch (\Exception $e) {
            \Log::warning('UTF-8 cleaning failed: ' . $e->getMessage());
            return '[Content could not be processed]';
        }
    }
    
    /**
     * Send a new message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048'
        ]);
        
        $imageUrl = null;
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('chat-images', $imageName, 'public');
            $imageUrl = Storage::url($imagePath);
        }
        
        // Clean and validate UTF-8 encoding for message content
        $cleanMessage = $this->aggressiveUtf8Clean($request->message);
        
        // Prepare message data
        $messageData = [
            'message' => $cleanMessage,
            'image_url' => $imageUrl
        ];
        
        // Dispatch job to queue for async processing
        ProcessChatMessage::dispatch(
            $messageData,
            Auth::id(),
            $request->receiver_id
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Message queued for processing'
        ]);
    }
    
    /**
     * Get unread message count for current user
     */
    public function getUnreadCount()
    {
        try {
            // Get unread counts grouped by sender
            $unreadCounts = Message::unreadForUser(Auth::id())
                ->selectRaw('user_id, COUNT(*) as count')
                ->groupBy('user_id')
                ->pluck('count', 'user_id')
                ->toArray();
            
            $totalCount = Message::unreadForUser(Auth::id())->count();
            
            return response()->json([
                'success' => true,
                'count' => $totalCount,
                'unread_counts' => $unreadCounts
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting unread count: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'count' => 0,
                'unread_counts' => []
            ]);
        }
    }
    
    /**
     * Mark messages as read
     */
    public function markAsRead(Request $request, $userId)
    {
        Message::where('user_id', $userId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return response()->json(['success' => true]);
    }
}
