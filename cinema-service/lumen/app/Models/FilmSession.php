<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilmSession extends Model
{
    protected $table = 'film_session';

    public $timestamps = false;

    protected $fillable = [
        'session_uid',
        'film_uid',
        'total_seats',
        'booked_seats',
        'date',
        'cinema_id',
    ];

    protected $casts = [
        'session_uid' => 'string',
        'film_uid' => 'string',
        'total_seats' => 'integer',
        'booked_seats' => 'integer',
        'date' => 'datetime',
        'cinema_id' => 'integer',
    ];
}
