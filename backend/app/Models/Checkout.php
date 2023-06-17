<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Checkout extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'product_id',
        'status',
        'claim',
        'url',
        'expire_at'
    ];


    public function product(){
        return $this->belongsTo(Product::class);
    }
}