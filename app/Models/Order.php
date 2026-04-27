<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_number', 'total_amount', 'status', 'payment_status',
        'payment_receipt', 'payment_method',
        'shipping_name', 'shipping_phone', 'shipping_address',
        'city', 'postal_code',
        'delivery_date', 'delivery_time', 'delivery_notes', 'gift_message',
        'tracking_number', 'courier',
    ];

    protected $casts = ['total_amount' => 'decimal:2', 'delivery_date' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function deliveryArea()
    {
        return $this->belongsTo(DeliveryArea::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}