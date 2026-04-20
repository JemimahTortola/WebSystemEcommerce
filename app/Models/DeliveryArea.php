<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryArea extends Model
{
    use HasFactory;

    protected $fillable = ['city', 'province', 'delivery_fee', 'same_day_cutoff', 'is_active'];

    protected $casts = ['delivery_fee' => 'decimal:2', 'is_active' => 'boolean'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
