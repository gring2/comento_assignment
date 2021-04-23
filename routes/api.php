<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

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

Route::post('/auth/register', [AuthController::class, 'register'])->name('api.register');

Route::post('/auth/login', [AuthController::class, 'login'])->name('api.login');

Route::get('/test_auth', function () {
    return 'ping';
})->middleware('auth:sanctum')->name('api.test');

Route::name('api.')->middleware('auth:sanctum')->group(function() {
    Route::resource('post', \App\Http\Controllers\PostController::class);
});
