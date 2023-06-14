<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Currency;
use Stripe\StripeClient;
use Illuminate\Http\Request;

class CouponController extends Controller
{

    public function index(){
        $coupon = Coupon::get();
        $coupon = $coupon->map(function($data){
            $data->created = $data->created_at->format('D d/m/Y H:i:s A');
            return $data;
        });
        return response()->json([
            'status' => 201,
            'data' => $coupon
        ], 201);
    }

    public function store(Request $request){
        $request->validate([
            'percent' => 'required|numeric',
            'currency' => 'required|numeric',
            'code' => 'required|unique:coupons,code',
            'max' => 'nullable|numeric'
        ]);

        $currency = Currency::find($request->currency);
        
        $stripe = new StripeClient(config('app.stripe'));
        $coupon = $stripe->coupons->create([
            'percent_off' => $request->percent,
            'name' =>  $request->percent."% off",
            'currency' => $currency->code
        ]);

        if($request->max == 0){
            $promo = $stripe->promotionCodes->create([
                'coupon' => $coupon['id'],
                'code' => strtoupper($request->code)
            ]);
        }else{
            $promo = $stripe->promotionCodes->create([
                'coupon' => $coupon['id'],
                'code' => strtoupper($request->code),
                "max_redemptions" => $request->max
            ]);
        }

        Coupon::create([
            'coupon_id' => $coupon['id'],
            'coupon_name' => $request->percent."% off",
            'currency_id' => $currency->id,
            'percent' => $request->percent,
            'promo_id' => $promo['id'],
            'code' => strtoupper($request->code),
            'max' => $request->max
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Coupon has been created!'
        ], 201);
    }
}
