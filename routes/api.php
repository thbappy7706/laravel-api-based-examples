<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('test-posts', function () {
    $data = collect(Http::get('https://jsonplaceholder.typicode.com/posts')->json())->take(50);
    return $data->map(function ($item) {
        unset($item['userId']);
        return $item;
    });
});
