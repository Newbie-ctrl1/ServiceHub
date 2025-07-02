<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Models\Message;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of orders for admin
     */
    public function index()
    {
        // Get orders for services owned by current admin
        $orders = Order::with(['user', 'service'])
            ->whereHas('service', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('Store.admin.orders', compact('orders'));
    }
    
    /**
     * Create a new order
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'notes' => 'nullable|string|max:500'
        ]);
        
        $service = Service::findOrFail($request->service_id);
        
        // Generate unique order number
        $orderNumber = 'ORD-' . strtoupper(Str::random(8));
        
        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'service_id' => $service->id,
            'order_number' => $orderNumber,
            'status' => Order::STATUS_PENDING,
            'total_price' => $service->price,
            'notes' => $request->notes
        ]);
        
        // Create notification for new order
        NotificationService::createOrderNotification($order);
        
        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat',
            'order_id' => $order->id,
            'order_number' => $order->order_number
        ]);
    }
    
    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);
        
        $order = Order::whereHas('service', function($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);
        
        $oldStatus = $order->status;
        
        $order->update([
            'status' => $request->status
        ]);
        
        // Create notification for order status update
        NotificationService::createOrderStatusNotification($order, $oldStatus);
        
        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diupdate'
        ]);
    }
    
    /**
     * Get order details
     */
    public function show($id)
    {
        $order = Order::with(['user', 'service'])
            ->whereHas('service', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->findOrFail($id);
            
        return response()->json($order);
    }
    
    /**
     * Delete an order
     */
    public function destroy($id)
    {
        $order = Order::whereHas('service', function($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);
        
        $order->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dihapus'
        ]);
    }
    
    /**
     * Send notification to customer
     */
    public function sendNotification(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);
        
        $order = Order::with(['user', 'service'])
            ->whereHas('service', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->findOrFail($id);
        
        // Send message to customer through chat system
        $message = Message::create([
            'user_id' => Auth::id(), // Admin (sender)
            'receiver_id' => $order->user_id, // Customer (receiver)
            'message' => $request->message,
            'is_read' => false
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dikirim ke chat pelanggan',
            'message_id' => $message->id
        ]);
    }
}