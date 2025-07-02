<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get notifications for the authenticated user
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Get unread count
        $unreadCount = NotificationService::getUnreadCount($user->id);
        
        return view('Notification.index', compact('notifications', 'unreadCount'));
    }
    
    /**
     * Get notifications as JSON (for AJAX requests)
     */
    public function getNotifications(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 10);
        $type = $request->get('type');
        
        $query = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');
        
        if ($type) {
            $query->where('type', $type);
        }
        
        $notifications = $query->limit($limit)->get();
        
        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'data' => $notification->data,
                    'is_read' => $notification->is_read,
                    'time_ago' => $notification->time_ago,
                    'icon' => $notification->icon,
                    'color' => $notification->color,
                    'created_at' => $notification->created_at->format('Y-m-d H:i:s')
                ];
            }),
            'unread_count' => NotificationService::getUnreadCount($user->id)
        ]);
    }
    
    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        
        if ($notification) {
            $notification->markAsRead();
            
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil ditandai sebagai dibaca'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Notifikasi tidak ditemukan'
        ], 404);
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        NotificationService::markAllAsRead($user->id);
        
        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi berhasil ditandai sebagai dibaca'
        ]);
    }
    
    /**
     * Delete a notification
     */
    public function delete($id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        
        if ($notification) {
            $notification->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil dihapus'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Notifikasi tidak ditemukan'
        ], 404);
    }
    
    /**
     * Get unread notifications count
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $count = NotificationService::getUnreadCount($user->id);
        
        return response()->json([
            'unread_count' => $count
        ]);
    }
}
