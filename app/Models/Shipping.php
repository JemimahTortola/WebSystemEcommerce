<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_name', 'shipping_phone', 'shipping_address',
        'city', 'postal_code', 'delivery_date', 'delivery_time',
        'delivery_notes', 'delivery_area_id'
    ];

    protected $casts = [
        'delivery_date' => 'date',
    ];

    public function deliveryArea()
    {
        return $this->belongsTo(DeliveryArea::class);
    }
}
