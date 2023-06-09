<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Stripe\StripeClient;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        return response()->json([
            'status' => 201,
            'data' => Product::latest()->with('server')->get()
        ], 201);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|max:255|unique:products,name',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:255',
            'server_id' => 'required|numeric',
            'price' => 'required|numeric',
            'description' => 'required',
            'currency' => 'required|max:3',
        ]);

        $stripe = new StripeClient(config('app.stripe'));
        $product = $stripe->products->create([
            'name' => $request->name
        ]);
        $price = $stripe->prices->create([
            'unit_amount' => $request->price * 100,
            'currency' => $request->currency,
            'product' => $product['id'],
        ]);
        $paymentlink = $stripe->paymentLinks->create([
            'line_items' => [
                [
                    'price' => $price['id'],
                    'quantity' => 1
                ],
            ],
            'allow_promotion_codes' => true
        ]);
        Product::create([
            'name' => $request->name,
            'server_id' => $request->server_id,
            'currency' => $request->currency,
            'product_id' => $product['id'],
            'price_id' => $price['id'],
            'price' => $request->price,
            'stripe_url' => $paymentlink['url'],
            'description' => $request->description,
            'image' => $request->image->store('product_images', 'public_disk'),
        ]);
        return response()->json([
            'status' => 201,
            'message' => 'Product has been created!'
        ], 201);
    }
}
