<?php

namespace App\Services;

use App\Exceptions\CinemaNotFoundException;
use App\Exceptions\CouldNotGetFilmsException;
use App\Exceptions\FilmSessionNoEmptySeatsException;
use App\Exceptions\FilmSessionNotFoundException;
use App\Models\Cinema;
use App\Models\FilmSession;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

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

    public function bookSeat(string $cinemaUid, string $filmUid, string $dateString): void
    {
        try {
            $cinema = Cinema::where('cinema_uid', $cinemaUid)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new CinemaNotFoundException($cinemaUid, 'Could not find cinema.', 0, $e);
        }

        try {
            $filmSession = FilmSession::where('cinema_id', $cinema->id)
                ->where('film_uid', $filmUid)
                ->where('date', $dateString)
                ->firstOrFail();
            $filmSession->increment('booked_seats');
        } catch (ModelNotFoundException $e) {
            throw new FilmSessionNotFoundException($cinemaUid, $filmUid, $dateString, 'Could not find film session.', 0, $e);
        } catch (QueryException $e) {
            if ($e->getCode() === 23514) {
                // @see https://www.postgresql.org/docs/current/errcodes-appendix.html
                throw new FilmSessionNoEmptySeatsException($cinemaUid, $filmUid, $dateString, 'All seats are booked.', 0, $e);
            } else {
                throw $e;
            }
        }
    }
}
