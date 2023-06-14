<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id',
        'coupon_name',
        'currency_id',
        'percent',
        'promo_id',
        'code',
        'max',
        'is_active'
    ];

    public function currency(){
        return $this->belongsTo(Currency::class);
    }
}