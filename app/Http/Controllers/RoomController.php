<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function createRoomView()
    {
        return view("/createroom");
    } 

    public function storeRoom(Request $request)
    {
        $room = $request->validate([
            "name"=> "required",
            "location"=> "required",
            "capacity"=> "required",
            "status"=> "required",
        ]);
    }
}
