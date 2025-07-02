<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at',
        'related_id',
        'related_type'
    ];
    
    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime'
    ];
    
    // Notification types
    const TYPE_ORDER_NEW = 'order_new';
    const TYPE_ORDER_PROCESSING = 'order_processing';
    const TYPE_ORDER_COMPLETED = 'order_completed';
    const TYPE_ORDER_CANCELLED = 'order_cancelled';
    const TYPE_PAYMENT_SUCCESS = 'payment_success';
    const TYPE_PAYMENT_FAILED = 'payment_failed';
    const TYPE_WALLET_TOPUP = 'wallet_topup';
    const TYPE_WALLET_TRANSFER = 'wallet_transfer';
    const TYPE_SERVICE_CREATED = 'service_created';
    const TYPE_SERVICE_UPDATED = 'service_updated';
    const TYPE_SERVICE_DELETED = 'service_deleted';
    const TYPE_PROMO = 'promo';
    const TYPE_SYSTEM = 'system';
    
    /**
     * Get the user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the related model (polymorphic relationship).
     */
    public function related()
    {
        return $this->morphTo();
    }
    
    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
    
    /**
     * Scope for read notifications.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }
    
    /**
     * Scope for specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
    
    /**
     * Mark notification as read.
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => Carbon::now()
        ]);
    }
    
    /**
     * Get formatted time ago.
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
    
    /**
     * Get notification icon based on type.
     */
    public function getIconAttribute()
    {
        $icons = [
            self::TYPE_ORDER_NEW => 'fa-shopping-cart',
            self::TYPE_ORDER_PROCESSING => 'fa-cog',
            self::TYPE_ORDER_COMPLETED => 'fa-check-circle',
            self::TYPE_ORDER_CANCELLED => 'fa-times-circle',
            self::TYPE_PAYMENT_SUCCESS => 'fa-credit-card',
            self::TYPE_PAYMENT_FAILED => 'fa-exclamation-triangle',
            self::TYPE_WALLET_TOPUP => 'fa-wallet',
            self::TYPE_WALLET_TRANSFER => 'fa-exchange-alt',
            self::TYPE_SERVICE_CREATED => 'fa-plus-circle',
            self::TYPE_SERVICE_UPDATED => 'fa-edit',
            self::TYPE_SERVICE_DELETED => 'fa-trash',
            self::TYPE_PROMO => 'fa-tag',
            self::TYPE_SYSTEM => 'fa-bell'
        ];
        
        return $icons[$this->type] ?? 'fa-bell';
    }
    
    /**
     * Get notification color based on type.
     */
    public function getColorAttribute()
    {
        $colors = [
            self::TYPE_ORDER_NEW => 'info',
            self::TYPE_ORDER_PROCESSING => 'warning',
            self::TYPE_ORDER_COMPLETED => 'success',
            self::TYPE_ORDER_CANCELLED => 'danger',
            self::TYPE_PAYMENT_SUCCESS => 'success',
            self::TYPE_PAYMENT_FAILED => 'danger',
            self::TYPE_WALLET_TOPUP => 'info',
            self::TYPE_WALLET_TRANSFER => 'info',
            self::TYPE_SERVICE_CREATED => 'success',
            self::TYPE_SERVICE_UPDATED => 'warning',
            self::TYPE_SERVICE_DELETED => 'danger',
            self::TYPE_PROMO => 'info',
            self::TYPE_SYSTEM => 'info'
        ];
        
        return $colors[$this->type] ?? 'info';
    }
}