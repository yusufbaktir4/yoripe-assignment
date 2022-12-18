<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/getToken', [\App\Http\Controllers\Api\AuthController::class, 'getToken']);

Route::middleware('auth:sanctum')->group(function () {
    // Route::post('/deleteToken', [\App\Http\Controllers\Api\AuthController::class, 'deleteToken']);
    Route::resource('/posts', \App\Http\Controllers\Api\PostController::class);
    Route::resource('/users', \App\Http\Controllers\Api\UsersController::class);
});

Route::middleware('admin')->group(function () {
    Route::resource('/users', \App\Http\Controllers\Api\UsersController::class);
});
