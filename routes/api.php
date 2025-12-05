<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TimeSlotController;
use App\Http\Controllers\ReservationController;

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);

// public resources
Route::get('rooms',[RoomController::class,'index']);
Route::get('rooms/{id}',[RoomController::class,'show']);
Route::get('timeslots',[TimeSlotController::class,'index']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('me',[AuthController::class,'me']);
    Route::post('logout',[AuthController::class,'logout']);

    // reservations
    Route::get('reservations',[ReservationController::class,'index']);
    Route::get('reservations/{id}',[ReservationController::class,'show']);
    Route::post('reservations',[ReservationController::class,'store']);
    Route::post('reservations/{id}/cancel',[ReservationController::class,'cancel']);

    // admin-only
    Route::middleware('role:admin')->group(function(){
        Route::post('rooms',[RoomController::class,'store']);
        Route::put('rooms/{id}',[RoomController::class,'update']);
        Route::delete('rooms/{id}',[RoomController::class,'destroy']);

        Route::post('timeslots',[TimeSlotController::class,'store']);

        Route::post('reservations/{id}/status',[ReservationController::class,'updateStatus']);
        Route::delete('reservations/{id}',[ReservationController::class,'destroy']);
    });
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
