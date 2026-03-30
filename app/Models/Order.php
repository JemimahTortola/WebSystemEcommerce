<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'shipping_address',
        'shipping_name',
        'shipping_phone',
        'notes',
        'is_archived',
        'tracking_number',
        'courier',
        'estimated_delivery',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'is_archived' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function tracking()
    {
        return $this->hasMany(ShippingTracking::class)->orderBy('created_at', 'desc');
    }

    public function latestTracking()
    {
        return $this->hasOne(ShippingTracking::class)->latestOfMany();
    }

    public function hasTracking(): bool
    {
        return !empty($this->tracking_number);
    }

    public function getStatusProgress(): float
    {
        switch ($this->status) {
            case 'pending':
                return 10;
            case 'processing':
                return 30;
            case 'shipped':
                return 60;
            case 'delivered':
                return 100;
            case 'cancelled':
                return 0;
            default:
                return 0;
        }
    }
}
