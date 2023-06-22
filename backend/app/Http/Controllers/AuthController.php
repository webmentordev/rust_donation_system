<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|unique:users,email|max:255|email',
            'password' => 'required|string|confirmed|min:5|max:255',
            'steam_id' => 'required|numeric|unique:users,steam_id'
        ]);

        if(strlen((string)$request->steam_id) != 17){
            return response()->json([
                "message" => 'Invalid Steam format',
                "errors" => [
                    "steam_id" => "Steam id lenght should be 17"
                ]
            ], 422);
        }

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'steam_id' => $fields['steam_id'],
            'password' => bcrypt($fields['password']),
        ]);
        $token = $user->createToken('mytoken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }

    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        $user = User::where('email', $fields['email'])->first();
        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response([
                'message' => 'Bad crads'
            ], 401);
        }
        $token = $user->createToken('mytoken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }
    
    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return [
            'status' => 201,
            'message' => 'Logged Out'
        ];
    }
}