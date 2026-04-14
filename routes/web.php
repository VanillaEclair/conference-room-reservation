<?php

use App\Http\Controllers\AcceptingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});


Route::post('/create-reservation', [ReservationController::class,'createReservation']);

Route::get('/accepting',[AcceptingController::class,'index']);


//ROOM CONTROLLERS
Route::get('/create-room-view',[RoomController::class,'createRoomView'])->name('room.view');
Route::post('/create-room',[RoomController::class,'storeRoom']);

//USER CONTROLLERS

Route::get('/create-view',[UserController::class,'createView'])->name('user.create');

Route::post('/login',[UserController::class,'login']);

Route::post('/create-user',[UserController::class,'createUser']);  

Route::post('/logout',[UserController::class,'logout']);

Route::post('/availability',[ReservationController::class,'checkSched']);