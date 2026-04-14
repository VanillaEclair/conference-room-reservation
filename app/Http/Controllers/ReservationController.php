<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{

//Add user to reservation relationship
    public function createReservation(Request $request)
    {
        $incomingFields = $request->validate([
            'purpose'        => ['required'],
            'title'        => ['required'],
            'start_datetime' => ['required'],
            'end_datetime'   => ['required'],
            'status'         => ['nullable'],
            'room_id'        => ['nullable'],
            'pdf'            => ['nullable'],
        ]);


        $incomingFields['start_datetime'] = Carbon::parse($incomingFields['start_datetime']);
        $incomingFields['end_datetime']   = Carbon::parse($incomingFields['end_datetime']);
        $incomingFields['user_id'] = Auth::id();
        $incomingFields['status'] = 'Pending';


        //sanitize data later
        // dd($incomingFields);

        //Check for scheduling error


        // $isSchedConflict = Reservation::where('start_datetime', '<', $incomingFields['end_datetime'])->where('end_datetime', '>', $incomingFields['start_datetime'])->where('room_id', '=', $incomingFields['room_id'])->exists();
        
        // $schedError = Reservation::where('purpose', '=', 'SSSSS$')->exists();
      
        $isSchedConflict = $this->isRoomAvailable($incomingFields['room_id'], $incomingFields['start_datetime'], $incomingFields['end_datetime']);

        if($isSchedConflict)
        {
            return back()->withErrors(['start_datetime' => 'Room is already booked for this time slot']);

        }

        Reservation::create($incomingFields);
        return redirect('/accepting');


    }

    public function showReservations(Reservation $reservation)
    {
        return view('accepting', ['reservation' => $reservation]);       
    }

    public function checkSched(Request $request)
    {
        $available = $this->isRoomAvailable(
            $request->room_id,
            $request->starttime,
            $request->endtime);

        return response()->json(['available'=> $available]);

    }

    //data
    private function isRoomAvailable($room_id, $start_datetime, $end_datetime)
{
    return !Reservation::where('room_id', $room_id)
        ->where('start_datetime', '<', $end_datetime)
        ->where('end_datetime', '>', $start_datetime)
        ->exists();
}
}
