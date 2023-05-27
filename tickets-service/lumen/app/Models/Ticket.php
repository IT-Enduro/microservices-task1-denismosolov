<?php

namespace App\Models;

use App\Enums\TicketStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';

    public $timestamps = false;

    protected $fillable = [
        'ticket_uid',
        'film_uid',
        'session_uid',
        'user_name', // @todo check length
        'date',
        'status',
        'row',
        'seat',
    ];

    protected $casts = [
        'ticket_uid' => 'string',
        'film_uid' => 'string',
        'session_uid' => 'string',
        'user_name' => 'string',
        'date' => 'datetime',
        'status' => TicketStatusEnum::class,
        'row' => 'integer',
        'seat' => 'integer',
    ];

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $attributes = parent::toArray();

        return [
            'ticketUid' => $attributes['ticket_uid'],
            'status' => $attributes['status'],
            'date' => $this->date->format('Y-m-d\TH:i:s'),
            'row' => $attributes['row'],
            'seat' => $attributes['seat'],
        ];
    }
}
