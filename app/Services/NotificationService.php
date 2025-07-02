<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    /**
     * Create notification for new order (for admin)
     */
    public static function createOrderNotification(Order $order)
    {
        // Notifikasi untuk admin yang memiliki service
        $service = $order->service;
        if ($service && $service->user) {
            self::createNotification(
                $service->user_id,
                Notification::TYPE_ORDER_NEW,
                'Pesanan Baru',
                "Anda mendapat pesanan baru untuk layanan '{$service->title}' dari {$order->user->name}",
                [
                    'order_id' => $order->id,
                    'service_title' => $service->title,
                    'customer_name' => $order->user->name,
                    'amount' => $order->total_price
                ],
                $order->id,
                Order::class
            );
        }
        
        // Notifikasi untuk customer
        self::createNotification(
            $order->user_id,
            Notification::TYPE_ORDER_NEW,
            'Pesanan Berhasil Dibuat',
            "Pesanan Anda untuk layanan '{$service->title}' telah berhasil dibuat dan menunggu konfirmasi",
            [
                'order_id' => $order->id,
                'service_title' => $service->title,
                'amount' => $order->total_price,
                'status' => $order->status
            ],
            $order->id,
            Order::class
        );
    }
    
    /**
     * Create notification for order status update
     */
    public static function createOrderStatusNotification(Order $order, $oldStatus)
    {
        $statusMessages = [
            Order::STATUS_PROCESSING => [
                'title' => 'Pesanan Sedang Diproses',
                'message' => "Pesanan Anda untuk layanan '{$order->service->title}' sedang diproses"
            ],
            Order::STATUS_COMPLETED => [
                'title' => 'Pesanan Selesai',
                'message' => "Pesanan Anda untuk layanan '{$order->service->title}' telah selesai"
            ],
            Order::STATUS_CANCELLED => [
                'title' => 'Pesanan Dibatalkan',
                'message' => "Pesanan Anda untuk layanan '{$order->service->title}' telah dibatalkan"
            ]
        ];
        
        if (isset($statusMessages[$order->status])) {
            $statusData = $statusMessages[$order->status];
            
            // Notifikasi untuk customer
            self::createNotification(
                $order->user_id,
                'order_' . strtolower($order->status),
                $statusData['title'],
                $statusData['message'],
                [
                    'order_id' => $order->id,
                    'service_title' => $order->service->title,
                    'old_status' => $oldStatus,
                    'new_status' => $order->status,
                    'amount' => $order->total_price
                ],
                $order->id,
                Order::class
            );
            
            // Notifikasi untuk admin jika pesanan selesai
            if ($order->status === Order::STATUS_COMPLETED) {
                $service = $order->service;
                if ($service && $service->user) {
                    self::createNotification(
                        $service->user_id,
                        Notification::TYPE_ORDER_COMPLETED,
                        'Pesanan Selesai',
                        "Pesanan untuk layanan '{$service->title}' telah selesai. Pembayaran akan segera diproses.",
                        [
                            'order_id' => $order->id,
                            'service_title' => $service->title,
                            'customer_name' => $order->user->name,
                            'amount' => $order->total_price
                        ],
                        $order->id,
                        Order::class
                    );
                }
            }
        }
    }
    
    /**
     * Create notification for wallet transaction
     */
    public static function createWalletTransactionNotification(Transaction $transaction)
    {
        $user = $transaction->user;
        
        if ($transaction->status === 'completed') {
            // Notifikasi untuk pengirim
            if ($transaction->receiver_id) {
                // Transfer antar user
                $receiver = User::find($transaction->receiver_id);
                
                self::createNotification(
                    $transaction->user_id,
                    Notification::TYPE_WALLET_TRANSFER,
                    'Transfer Berhasil',
                    "Transfer sebesar Rp " . number_format($transaction->amount, 0, ',', '.') . " ke {$receiver->name} berhasil",
                    [
                        'transaction_id' => $transaction->id,
                        'amount' => $transaction->amount,
                        'receiver_name' => $receiver->name,
                        'type' => 'transfer_out'
                    ],
                    $transaction->id,
                    Transaction::class
                );
                
                // Notifikasi untuk penerima
                self::createNotification(
                    $transaction->receiver_id,
                    Notification::TYPE_WALLET_TRANSFER,
                    'Dana Diterima',
                    "Anda menerima transfer sebesar Rp " . number_format($transaction->amount, 0, ',', '.') . " dari {$user->name}",
                    [
                        'transaction_id' => $transaction->id,
                        'amount' => $transaction->amount,
                        'sender_name' => $user->name,
                        'type' => 'transfer_in'
                    ],
                    $transaction->id,
                    Transaction::class
                );
            } else {
                // Top up wallet
                self::createNotification(
                    $transaction->user_id,
                    Notification::TYPE_WALLET_TOPUP,
                    'Top Up Berhasil',
                    "Top up wallet sebesar Rp " . number_format($transaction->amount, 0, ',', '.') . " berhasil",
                    [
                        'transaction_id' => $transaction->id,
                        'amount' => $transaction->amount,
                        'type' => 'topup'
                    ],
                    $transaction->id,
                    Transaction::class
                );
            }
        } elseif ($transaction->status === 'failed') {
            self::createNotification(
                $transaction->user_id,
                Notification::TYPE_PAYMENT_FAILED,
                'Transaksi Gagal',
                "Transaksi wallet sebesar Rp " . number_format($transaction->amount, 0, ',', '.') . " gagal diproses",
                [
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'type' => 'failed'
                ],
                $transaction->id,
                Transaction::class
            );
        }
    }
    
    /**
     * Create notification for service management
     */
    public static function createServiceNotification(Service $service, $action)
    {
        $messages = [
            'created' => [
                'title' => 'Layanan Berhasil Dibuat',
                'message' => "Layanan '{$service->title}' berhasil dibuat dan sedang menunggu persetujuan",
                'type' => Notification::TYPE_SERVICE_CREATED
            ],
            'updated' => [
                'title' => 'Layanan Berhasil Diperbarui',
                'message' => "Layanan '{$service->title}' berhasil diperbarui",
                'type' => Notification::TYPE_SERVICE_UPDATED
            ],
            'deleted' => [
                'title' => 'Layanan Dihapus',
                'message' => "Layanan '{$service->title}' telah dihapus",
                'type' => Notification::TYPE_SERVICE_DELETED
            ]
        ];
        
        if (isset($messages[$action])) {
            $messageData = $messages[$action];
            
            self::createNotification(
                $service->user_id,
                $messageData['type'],
                $messageData['title'],
                $messageData['message'],
                [
                    'service_id' => $service->id,
                    'service_title' => $service->title,
                    'action' => $action
                ],
                $service->id,
                Service::class
            );
        }
    }
    
    /**
     * Create notification for payment success
     */
    public static function createPaymentNotification(Order $order)
    {
        // Notifikasi untuk customer
        self::createNotification(
            $order->user_id,
            Notification::TYPE_PAYMENT_SUCCESS,
            'Pembayaran Berhasil',
            "Pembayaran untuk pesanan '{$order->service->title}' berhasil diproses",
            [
                'order_id' => $order->id,
                'service_title' => $order->service->title,
                'amount' => $order->total_price
            ],
            $order->id,
            Order::class
        );
        
        // Notifikasi untuk admin pemilik service
        $service = $order->service;
        if ($service && $service->user) {
            self::createNotification(
                $service->user_id,
                Notification::TYPE_PAYMENT_SUCCESS,
                'Pembayaran Diterima',
                "Pembayaran untuk pesanan '{$service->title}' telah diterima. Dana akan ditransfer ke wallet Anda.",
                [
                    'order_id' => $order->id,
                    'service_title' => $service->title,
                    'customer_name' => $order->user->name,
                    'amount' => $order->total_price
                ],
                $order->id,
                Order::class
            );
        }
    }
    
    /**
     * Create a notification
     */
    private static function createNotification(
        $userId,
        $type,
        $title,
        $message,
        $data = [],
        $relatedId = null,
        $relatedType = null
    ) {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'related_id' => $relatedId,
            'related_type' => $relatedType
        ]);
    }
    
    /**
     * Get notifications for user
     */
    public static function getUserNotifications($userId, $limit = 10)
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get unread notifications count
     */
    public static function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }
    
    /**
     * Mark notification as read
     */
    public static function markAsRead($notificationId, $userId)
    {
        return Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }
    
    /**
     * Mark all notifications as read
     */
    public static function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }
}