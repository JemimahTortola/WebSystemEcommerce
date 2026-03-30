<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreInfo extends Model
{
    use HasFactory;

    protected $fillable = ['store_name', 'store_email', 'store_phone', 'store_address', 'store_description', 'logo'];
}
