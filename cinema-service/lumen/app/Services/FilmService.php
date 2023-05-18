<?php

namespace App\Services;

use App\Exceptions\CouldNotGetFilmsException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class FilmService
{
    private Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param array $filmUids
     * @return array
     * @throws CouldNotGetFilmsException
     */
    public function getFilms(array $filmUids): array
    {
        if (empty($filmUids)) {
            return [];
        }

        try {
            // @todo use Guzzle Retry Middleware to retry
            $response = $this->httpClient->get('/api/v1/films');
            if ($response->getStatusCode() === 200) {
                $filmsWithPaginationResponse = json_decode($response->getBody(), true, 10, JSON_THROW_ON_ERROR);
                $filmResponses = $filmsWithPaginationResponse['items'];
                // @todo fetch by $filmUids instead of this
                $result = array_filter($filmResponses, fn(array $filmResponse) => in_array($filmResponse['filmUid'], $filmUids));
            } else {
                throw new CouldNotGetFilmsException($filmUids, 'Request Failed', CouldNotGetFilmsException::CODE_UNEXPECTED_HTTP_RESPONSE_CODE_FILMS_SERVICE);
            }
        } catch (GuzzleException $e) {
            throw new CouldNotGetFilmsException($filmUids, 'Request Failed', CouldNotGetFilmsException::CODE_GUZZLE_REQUEST_EXCEPTION, $e);
        } catch (JsonException $e) {
            throw new CouldNotGetFilmsException($filmUids, 'Request Failed', CouldNotGetFilmsException::CODE_COULD_NOT_UNSERIALIZE_RESPONSE_FILMS_SERVICE, $e);
        }

        return $result;
    }
}
