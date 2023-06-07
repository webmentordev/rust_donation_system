<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return ['Laravel' => 'Welcome to Nowhere'];
});

require __DIR__.'/auth.php';