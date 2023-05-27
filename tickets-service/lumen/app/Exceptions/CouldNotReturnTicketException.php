<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class CouldNotReturnTicketException extends Exception
{
    private string $ticketUid;

    public function __construct(string $ticketUid, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->ticketUid = $ticketUid;
    }
}
