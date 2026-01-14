<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BookmarkController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
//    Authentication Routes
    Route::post('register', [AuthController::class, 'register'])->name('api.v1.register');
    Route::post('login', [AuthController::class, 'login'])->name('api.v1.login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('api.v1.logout');
        Route::get('me', [AuthController::class, 'me'])->name('api.v1.me');

        Route::apiResource('tasks', TaskController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('posts', PostController::class);
        Route::apiResource('bookmarks', BookmarkController::class);
    });


});

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
