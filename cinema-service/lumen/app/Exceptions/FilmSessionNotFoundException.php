<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class FilmSessionNotFoundException extends Exception
{
    protected string $cinemaUid;
    protected string $filmUid;
    protected string $date;

    public function __construct(string $cinemaUid, string $filmUid, string $date, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->cinemaUid = $cinemaUid;
        $this->filmUid = $filmUid;
        $this->date = $date;
    }
}
