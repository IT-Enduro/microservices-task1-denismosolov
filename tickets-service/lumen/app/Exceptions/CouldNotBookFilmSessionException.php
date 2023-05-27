<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class CouldNotBookFilmSessionException extends Exception
{
    public const CODE_UNEXPECTED_HTTP_RESPONSE_CODE_CINEMA_SERVICE = 100;
    public const CODE_GUZZLE_REQUEST_EXCEPTION = 200;

    private string $sessionUid;

    public function __construct(string $sessionUid, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->sessionUid = $sessionUid;
    }
}
