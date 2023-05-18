<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class CouldNotGetFilmsException extends Exception
{
    public const CODE_UNEXPECTED_HTTP_RESPONSE_CODE_FILMS_SERVICE = 100;
    public const CODE_GUZZLE_REQUEST_EXCEPTION = 200;
    public const CODE_COULD_NOT_UNSERIALIZE_RESPONSE_FILMS_SERVICE = 300;

    /**
     * @var string[]
     */
    protected array $filmUids;

    public function __construct(array $filmUids, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->filmUids = $filmUids;
    }
}
