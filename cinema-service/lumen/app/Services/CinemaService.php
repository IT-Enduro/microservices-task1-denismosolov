<?php

namespace App\Services;

use App\Exceptions\CinemaNotFoundException;
use App\Exceptions\CouldNotGetFilmsException;
use App\Models\Cinema;
use App\Models\FilmSession;

class CinemaService
{
    private FilmService $filmService;

    public function __construct(FilmService $filmService)
    {
        $this->filmService = $filmService;
    }

    /**
     * @param string $cinemaUid
     * @return array
     * @throws CinemaNotFoundException
     * @throws CouldNotGetFilmsException
     */
    public function getFilmsInCinemaResponse(string $cinemaUid): array
    {
        $cinema = Cinema::where('cinema_uid', $cinemaUid)->first();

        if (!$cinema) {
            throw new CinemaNotFoundException($cinemaUid, 'Could not find cinema.');
        }

        $response = $cinema->toArray();
        $response['films'] = $this->filmService->getFilms(
            $cinema->filmSessions->map(fn(FilmSession $filmSession) => $filmSession->film_uid)->toArray()
        );

        return $response;
    }
}
