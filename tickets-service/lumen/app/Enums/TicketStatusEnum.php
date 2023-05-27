<?php

namespace App\Enums;

enum TicketStatusEnum: string
{
    case BOOKED = 'BOOKED';
    case CANCELED = 'CANCELED';
}
