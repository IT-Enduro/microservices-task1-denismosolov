<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class CinemaNotFoundException extends Exception
{
    protected string $cinemaUid;

    public function __construct(string $cinemaUid, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->cinemaUid = $cinemaUid;
    }
}
