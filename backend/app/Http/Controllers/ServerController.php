<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::get();
        $server_info = $servers->map(function ($data) {
            $data->name = $data->name;
            $data->image = config('app.url').'/storage/'.$data->image;
            $data->slug = $data->slug;
            $data->is_active = $data->is_active;
            $data->token = $data->token;
            $data->created = $data->created_at->diffForHumans().'-'.$data->created_at;
            return $data;
        });
        return response()->json([
            'data' => $server_info
        ], 200);
    }

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

    public function store(Request $request){
        $fields = $request->validate([
            'name' => 'required|max:150|unique:servers,name',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:255'
        ]);
        Server::create([
            'name' => $fields['name'],
            'slug' => strtolower(str_replace(' ', '-', $fields['name'])),
            'image' => $request->image->store('server_images', 'public_disk'),
            'token' => $this->randomPassword()
        ]);
        return response()->json([
            'status' => 201,
            'message' => 'Server has been created!'
        ], 201);
    }


    public function fetch()
    {
        $servers = Server::get();
        $server_info = $servers->map(function ($data) {
            $data->name = $data->name;
            $data->image = config('app.url').'/storage/'.$data->image;
            $data->slug = $data->slug;
            $data->is_active = $data->is_active;
            $data->created = $data->created_at->diffForHumans().'-'.$data->created_at;
            return $data;
        });
        return response()->json([
            'data' => $server_info
        ], 200);
    }
}