<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index(){
        $currency = Currency::latest()->get();
        $currency = $currency->map(function($data){
            $data->created = $data->created_at->format('D d/m/Y H:i:s A');
            return $data;
        });
        return response()->json([
            'data' => $currency
        ], 200);
    }
    public function store(Request $request){
        $request->validate([
            'code' => 'required|unique:currencies,code',
            'symbol' => 'required|max:1|unique:currencies,symbol'
        ]);
        Currency::create([
            'code' => $request->code,
            'symbol' => $request->symbol
        ]);
        return response()->json([
            'status' => 201,
            'message' => 'Currency has been created!'
        ], 201);
    }
}
