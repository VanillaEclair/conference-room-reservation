<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
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
        $incomingFields['user_id'] = 1;
        $incomingFields['room_id'] = 1;


        //sanitize data later
        // dd($incomingFields);
        Reservation::create($incomingFields);
        return redirect('/accepting');
    }

    public function showReservations(Reservation $reservation)
    {
        return view('accepting', ['reservation' => $reservation]);       
    }
}
