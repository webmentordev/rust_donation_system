<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\Product;
use Carbon\Carbon;
use Stripe\StripeClient;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{


    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 30; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }
    
    public function store($slug){
        $random_id = $this->randomPassword();
        $product = Product::where('slug', $slug)->get();
        $stripe = new StripeClient(config('app.stripe'));
        $checkout = $stripe->checkout->sessions->create([
            'success_url' => config('app.frontend').'/success/'.$random_id,
            'cancel_url' => config('app.frontend').'/cancel/'.$random_id,
            'line_items' => [
                [
                    'price' => $product[0]->price_id,
                    'quantity' => 1
                ]
            ],
            'mode' => 'payment',
        ]);
        Checkout::create([
            'user_id' => auth()->user()->id,
            'order_id' => $random_id,
            'product_id' => $product[0]->id,
            'url' => $checkout['url'],
            'expire_at' => Carbon::now()->addMonths(1)
        ]);
        return response()->json([
            'redirect' => $checkout['url']
        ], 201);
    }


    public function success(Checkout $checkout){
        if($checkout->status == 'pending'){
            $checkout->status = 'completed';
            $checkout->save();
            Checkout::where('user_id', auth()->user()->id)->where('status', 'pending')->delete();
            return response()->json([
                'data' => $checkout->load('product','product.currency')
            ], 201);
        }elseif($checkout->status == 'completed'){
            return response()->json([
                'data' => $checkout->load('product','product.currency')
            ], 201);
        }
    }

    public function cancel(Checkout $checkout){
        if($checkout->status == 'pending'){
            $checkout->status = 'cancelled';
            $checkout->save();
            Checkout::where('user_id', auth()->user()->id)->where('status', 'pending')->delete();
            return response()->json([
                'data' => $checkout->load('product','product.currency')
            ], 201);
        }elseif($checkout->status == 'cancelled'){
            return response()->json([
                'data' => $checkout->load('product','product.currency')
            ], 201);
        }
    }
}