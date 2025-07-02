<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'service_id',
        'order_number',
        'status',
        'total_price',
        'notes',
        'scheduled_at'
    ];
    
    protected $casts = [
        'scheduled_at' => 'datetime',
        'total_price' => 'decimal:2'
    ];
    
    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    
    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relationship with service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    
    // Fungsi ulasan telah dihapus
    
    // Scope for pending orders
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    
    // Scope for processing orders
    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }
    
    // Scope for completed orders
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
    
    // Scope for cancelled orders
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }
}