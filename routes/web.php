<?php

use App\Http\Controllers\AcceptingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});


Route::post('/store', [ReservationController::class,'store']);

Route::get('/accepting',[AcceptingController::class,'index']);




//USER CONTROLLERS

Route::get('/create',[UserController::class,'create'])->name('user.create');

Route::post('/login',[UserController::class,'login']);

Route::post('/store',[UserController::class,'store']);  

Route::post('/logout',[UserController::class,'logout']);