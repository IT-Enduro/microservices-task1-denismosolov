<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class CouldNotFindFilmSessionException extends Exception
{
    public const CODE_UNEXPECTED_HTTP_RESPONSE_CODE_CINEMA_SERVICE = 100;
    public const CODE_GUZZLE_REQUEST_EXCEPTION = 200;
    public const CODE_COULD_NOT_UNSERIALIZE_RESPONSE_CINEMA_SERVICE = 300;

    public function __construct(string $cinemaUid, string $filmUid, string $dataString, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->cinemaUid = $cinemaUid;
        $this->filmUid = $filmUid;
        $this->dateString = $dataString;
    }
}
