<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Server;
use App\Models\Product;
use Stripe\StripeClient;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $products = Product::latest()->with('server')->get();
        $product_info = $products->map(function ($data) {
            $data->created = $data->created_at->format('D d/m/Y H:i:s A');
            $data->image = config('app.url').'/storage/'.$data->image;
            return $data;
        });
        return response()->json([
            'status' => 201,
            'data' => $product_info
        ], 201);
    }

    public function store(Request $request){
        $fields = $request->validate([
            'name' => 'required|max:255|unique:products,name',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:255',
            'server_id' => 'required|numeric',
            'price' => 'required|numeric',
            'description' => 'required',
            'currency' => 'required|numeric|max:3',
        ]);

        $currency = Currency::find($request->currency);

        $stripe = new StripeClient(config('app.stripe'));
        $product = $stripe->products->create([
            'name' => $request->name
        ]);
        $price = $stripe->prices->create([
            'unit_amount' => $request->price * 100,
            'currency' => $currency->code,
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
            'currency_id' => $currency->id,
            'product_id' => $product['id'],
            'price_id' => $price['id'],
            'slug' => strtolower(str_replace(' ', '-', $request->name)),
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

    public function updateStatus($id){
        $result = Product::find($id);
        $value = !$result->is_active;
        if($value == 1){
            $newStatus = true;
        }else if($value == 0){
            $newStatus = false;
        }
        $result->is_active = $value;
        $result->save();
        $stripe = new StripeClient(config('app.stripe'));
        $stripe->products->update(
            $result->product_id,
            ['active' => $newStatus]
          );
        return response()->json([
            'message' => 'Package status has been changed!'
        ], 200);
    }

    public function product($product){
        $product = Product::where('slug', $product)->where('is_active', true)->with('currency')->get();
        if(count($product)){
            $product = $product->map(function ($data) {
                $data->image = config('app.url').'/storage/'.$data->image;
                $data->is_active = $data->is_active;
                $data->product_id = null;
                $data->price_id = null;
                return $data;
            });
            return response()->json([
                'data' => $product
            ], 200);
        }else{
            return response()->json([
                'data' => []
            ], 200);
        }
    }
}
