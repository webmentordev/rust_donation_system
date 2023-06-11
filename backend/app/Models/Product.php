<?php

namespace App\Models;

use App\Models\Server;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'server_id',
        'product_id',
        'price_id',
        'image',
        'slug',
        'stripe_url',
        'price',
        'is_active',
        'description',
        'currency'
    ];

    public function server(){
        return $this->belongsTo(Server::class);
    }
}
