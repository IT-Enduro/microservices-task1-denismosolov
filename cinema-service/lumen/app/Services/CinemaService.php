<?php

namespace App\Services;

use App\Exceptions\CinemaNotFoundException;
use App\Exceptions\CouldNotGetFilmsException;
use App\Models\Cinema;
use App\Models\FilmSession;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        try {
            $cinema = Cinema::where('cinema_uid', $cinemaUid)->firstOrFail();
            $response = $cinema->toArray();
            $response['films'] = $this->filmService->getFilms(
                $cinema->filmSessions->map(fn(FilmSession $filmSession) => $filmSession->film_uid)->toArray()
            );

            return $response;
        } catch (ModelNotFoundException $e) {
            throw new CinemaNotFoundException($cinemaUid, 'Could not find cinema.', 0, $e);
        }
    }
}
