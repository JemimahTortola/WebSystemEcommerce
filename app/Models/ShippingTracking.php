<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingTracking extends Model
{
    protected $fillable = [
        'order_id',
        'status',
        'title',
        'description',
        'location',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public static function getStatusIcon(string $status): string
    {
        return match($status) {
            'order_placed' => '📦',
            'processing' => '⚙️',
            'shipped' => '🚚',
            'out_for_delivery' => '🚛',
            'delivered' => '✅',
            'cancelled' => '❌',
            'on_hold' => '⏸️',
            'returned' => '↩️',
            default => '📍',
        };
    }

    public static function getStatusLabel(string $status): string
    {
        return match($status) {
            'order_placed' => 'Order Placed',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'on_hold' => 'On Hold',
            'returned' => 'Returned',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }
}
