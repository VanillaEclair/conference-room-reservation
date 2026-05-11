<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{

    public function createReservation(Request $request)
    {
        $incomingFields = $request->validate([
            'purpose' => ['required'],
            'title' => ['required'],
            'start_datetime' => ['required'],
            'end_datetime' => ['required'],
            'status' => ['nullable'],
            'room_id' => ['nullable'],
            'pdf' => ['nullable'],
        ]);


        $incomingFields['start_datetime'] = Carbon::parse($incomingFields['start_datetime']);
        $incomingFields['end_datetime'] = Carbon::parse($incomingFields['end_datetime']);
        $incomingFields['user_id'] = Auth::id();
        $incomingFields['status'] = 'Pending';


        //sanitize data later
        // dd($incomingFields);

        //Check for scheduling error


        // $isSchedConflict = Reservation::where('start_datetime', '<', $incomingFields['end_datetime'])->where('end_datetime', '>', $incomingFields['start_datetime'])->where('room_id', '=', $incomingFields['room_id'])->exists();

        // $schedError = Reservation::where('purpose', '=', 'SSSSS$')->exists();

        // $isSchedConflict = $this->isRoomAvailable($incomingFields['room_id'], $incomingFields['start_datetime'], $incomingFields['end_datetime']);

        // if (!$isSchedConflict) // ← also note: isRoomAvailable returns true when available
        // {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Room is already booked for this time slot'
        //     ], 422);
        // }

        Reservation::create($incomingFields);
        return response()->json(['success' => true]);



    }

    public function checkSched(Request $request, $id=null)
    {
        $available = $this->isRoomAvailable(
            $request->room_id,
            $request->starttime,
            $request->endtime,
            $id
        );

        return response()->json(['available' => $available]);

    }

    //CHANGING HERE
    private function isRoomAvailable($room_id, $start_datetime, $end_datetime, $id=null)
    {
        $query = Reservation::where('room_id', $room_id)
                ->where('start_datetime','<', $end_datetime)
                ->where('end_datetime', '>', $start_datetime);

        if($id)
        {
                $query->where('id', '!=', $id)->exists();
        }

        return !$query->exists();
    }


    public function Accept(Reservation $reservation)
    {
        $reservation->update(['status' => 'Accepted']);
        return response()->json(['success' => true]);
    }

    public function Decline(Reservation $reservation)
    {
        $reservation->update(['status' => 'Declined']);
        return response()->json(['success' => true]);
    }


    public function Edit(Request $request, Reservation $reservation)
    {
        $fields = $request->validate([
            'purpose' => ['required'],
            'title' => ['required'],
            'start_datetime' => ['required'],
            'end_datetime' => ['required'],
            'room_id' => ['nullable'],
        ]);


        $reservation->update($fields);
        return response()->json(['success' => true]);

    }

    public function Remove(Reservation $reservation)
    {
        $reservation->update(['status' => 'Deleted']);
        return response()->json(['success' => true]);
    }

    public function getReservation(Reservation $reservation)
    {
      return response()->json($reservation);
    }

}
