<?php

namespace App\Services;

use App\Exceptions\CinemaNotFoundException;
use App\Exceptions\FilmSessionNoEmptySeatsException;
use App\Exceptions\FilmSessionNotFoundException;
use App\Models\Cinema;
use App\Models\FilmSession;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class FilmSessionService
{
    /**
     * @param string $sessionUid
     * @return void
     * @throws FilmSessionNoEmptySeatsException
     * @throws FilmSessionNotFoundException
     */
    public function bookSeat(string $sessionUid): void
    {
        try {
            $filmSession = FilmSession::where('session_uid', $sessionUid)->firstOrFail();
            $filmSession->increment('booked_seats');
        } catch (QueryException $e) {
            if ($e->getCode() === '23514') {
                // @see https://www.postgresql.org/docs/current/errcodes-appendix.html
                throw new FilmSessionNoEmptySeatsException('All seats are booked.');
            } else {
                throw $e;
            }
        } catch (ModelNotFoundException $e) {
            throw new FilmSessionNotFoundException('Could not find cinema.');
        }
    }

    /**
     * @param string $cinemaUid
     * @param string $filmUid
     * @param string $dateString
     * @return FilmSession
     * @throws CinemaNotFoundException
     * @throws FilmSessionNotFoundException
     */
    public function getFilmSession(string $cinemaUid, string $filmUid, string $dateString): FilmSession
    {
        try {
            $cinema = Cinema::where('cinema_uid', $cinemaUid)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new CinemaNotFoundException($cinemaUid, 'Could not find cinema.', 0, $e);
        }

        try {
            return FilmSession::where('cinema_id', $cinema->id)
                ->where('film_uid', $filmUid)
                ->where('date', $dateString)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new FilmSessionNotFoundException('Could not find film session.');
        }
    }
}
