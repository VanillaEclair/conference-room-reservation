<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['start_datetime', 'end_datetime', 'purpose', 'title', 'status', 'user_id', 'room_id', 'pdf'];

            protected $casts = [
            'start_datetime' => 'datetime',
            'end_datetime'   => 'datetime',
        ];
}
