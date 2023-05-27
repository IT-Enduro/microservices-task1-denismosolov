<?php

namespace App\Services;

use App\Exceptions\CouldNotBookFilmSessionException;
use App\Exceptions\CouldNotFindFilmSessionException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class FilmSessionService
{
    private Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $sessionUid
     */
    public function book(string $sessionUid): void
    {
        try {
            // @todo use Guzzle Retry Middleware to retry
            $response = $this->httpClient->post(sprintf('/api/v1/film-session/%s/book-seat', $sessionUid));
            if ($response->getStatusCode() !== 204) {
                throw new CouldNotBookFilmSessionException($sessionUid, 'Request Failed', CouldNotBookFilmSessionException::CODE_UNEXPECTED_HTTP_RESPONSE_CODE_CINEMA_SERVICE);
            }
        } catch (GuzzleException $e) {
            throw new CouldNotBookFilmSessionException($sessionUid, 'Request Failed', CouldNotBookFilmSessionException::CODE_GUZZLE_REQUEST_EXCEPTION, $e);
        }
    }

    /**
     * @param string $cinemaUid
     * @param string $filmUid
     * @param string $dataString
     * @return string
     * @throws CouldNotFindFilmSessionException
     */
    public function getFilmSessionUid(string $cinemaUid, string $filmUid, string $dataString): string
    {
        try {
            // @todo use Guzzle Retry Middleware to retry
            $response = $this->httpClient->get('/api/v1/film-session', [
                'query' => [
                    'cinema_uid' => $cinemaUid,
                    'film_uid' => $filmUid,
                    'date' => $dataString,
                ],
            ]);
            if ($response->getStatusCode() !== 200) {
                throw new CouldNotFindFilmSessionException($cinemaUid, $filmUid, $dataString, 'Request Failed', CouldNotFindFilmSessionException::CODE_UNEXPECTED_HTTP_RESPONSE_CODE_CINEMA_SERVICE);
            }
            $filmSessionResponse = json_decode($response->getBody(), true, 10, JSON_THROW_ON_ERROR);

            return $filmSessionResponse['session_uid'];
        } catch (GuzzleException $e) {
            throw new CouldNotFindFilmSessionException($cinemaUid, $filmUid, $dataString, 'Request Failed', CouldNotFindFilmSessionException::CODE_GUZZLE_REQUEST_EXCEPTION, $e);
        } catch (JsonException $e) {
            throw new CouldNotFindFilmSessionException($cinemaUid, $filmUid, $dataString, 'Request Failed', CouldNotFindFilmSessionException::CODE_COULD_NOT_UNSERIALIZE_RESPONSE_CINEMA_SERVICE, $e);
        }
    }
}
