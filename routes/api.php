<?php

use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\InftorationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login',[AuthController::class, 'login'] );
    Route::post('register',[AuthController::class, 'register'] );
    Route::post('logout',[AuthController::class, 'logout'] );
    Route::post('refresh', [AuthController::class, 'refresh'] );
    Route::post('me', [AuthController::class, 'me'] );
    Route::post('me', [AuthController::class, 'me'] );
    Route::post('verifyOTP', [AuthController::class, 'verifyOTP'] );

    Route::post('forgot-password',[AuthController::class, 'forgot'] );
    Route::post('reset-password',[AuthController::class, 'reset'] );







});


Route::apiResource('categories', CategoriesController::class);


Route::apiResource('information', InftorationController::class);

Route::apiResource('Users', UserController::class);

