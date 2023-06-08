<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'server_id',
        'product_id',
        'price_id',
        'image',
        'stripe_url',
        'price',
        'is_active',
        'description'
    ];
}
