<?php

use App\Http\Controllers\AcceptingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::middleware('validUser')->group(function ()
{
    Route::post('/create-reservation', [ReservationController::class,'createReservation']);
    Route::post('/reservations/{reservation}/accept', [ReservationController::class, 'Accept']);
    Route::post('/reservations/{reservation}/decline', [ReservationController::class, 'Decline']);
    Route::put('/reservations/{reservation}/edit', [ReservationController::class, 'Edit']);
    Route::post('/reservations/{reservation}/remove', [ReservationController::class, 'Remove']);

    Route::get('/reservations-users', [UserController::class,'getUser']);

    Route::get('/reservations',[AcceptingController::class,'index']);
    Route::get('/reservation/{reservation}', [ReservationController::class,'getReservation']);

    //ROOM CONTROLLERS
    Route::post('/create-room',[RoomController::class,'storeRoom']);
    Route::get('/get-rooms',[RoomController::class,'getRooms']);

    //USER CONTROLLERS
    Route::post('/logout',[UserController::class,'logout']);
    Route::post('/availability',[ReservationController::class,'checkSched']);
    Route::post('/availability/{id}',[ReservationController::class,'checkSched']);
});



//USER CONTROLLERS PUBLIC
Route::post('/login',[UserController::class,'login']);
Route::post('/create-user',[UserController::class,'createUser']); 



Route::get('/api/user', function() {
    if(!auth()->user())
        {
            return response()->json(null, 401);
        }

    return response()->json(auth()->user());
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');