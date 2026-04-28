<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'product_name', 'price', 'quantity', 'subtotal', 'delivery_date'];

    protected $casts = ['price' => 'decimal:2', 'subtotal' => 'decimal:2', 'delivery_date' => 'date'];
    
    protected $appends = ['delivery_date_formatted'];
    
    public function getDeliveryDateFormattedAttribute()
    {
        return $this->delivery_date ? $this->delivery_date->format('M d, Y') : null;
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}