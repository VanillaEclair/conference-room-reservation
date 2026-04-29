<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class AcceptingController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('user')->latest()->get();
        return response()->json($reservations);

        // return view("accepting", ['reservations' => $reservations]);
    }
}
