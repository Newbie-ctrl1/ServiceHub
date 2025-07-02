<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'title',
        'short_description',
        'description',
        'full_description',
        'price',
        'min_time',
        'max_time',
        'highlights',
        'badge',
        'category',
        'location',
        'main_image'
        // 'additional_images' telah dihapus karena sekarang menggunakan tabel terpisah
    ];
    
    protected $casts = [
        // 'additional_images' => 'array', // Dihapus karena sekarang menggunakan tabel terpisah
        'highlights' => 'array',
        'price' => 'decimal:2'
    ];
    
    // Relationship with orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    // Relationship with additional images
    public function additionalImages()
    {
        return $this->hasMany(ServiceImage::class);
    }
    
    // Relationship with user (admin who created the service)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Fungsi ulasan telah dihapus
}