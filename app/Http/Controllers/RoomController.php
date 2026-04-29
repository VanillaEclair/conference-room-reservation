<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{

    public function storeRoom(Request $request)
    {
        $room = $request->validate([
            "name"=> "required",
            "location"=> "required",
            "capacity"=> "required",
            "status"=> "required",
        ]);

        Room::create($room);

        return response()->json(['success' => 'true']);
    }

    public function getRooms(){
        $rooms = Room::all();
        return response()->json($rooms);
    }
}
