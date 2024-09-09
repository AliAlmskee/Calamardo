<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoodController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

    Route::middleware('auth:sanctum')->group(function () {
        Route::put('/updateProfile',[AuthController::class,'updateProfile']);
        Route::post('/logout',[AuthController::class,'logout']);
        Route::apiResource('foods', FoodController::class)->except(['index', 'show','update']);
        Route::post('/update_food/{id}',[FoodController::class,'update']);

    });


    Route::apiResource('foods', FoodController::class)->only(['index', 'show']);
    Route::post('/login',[AuthController::class,'login']);

    Route::get('/image/{path}',[FoodController::class,'getImage']);
