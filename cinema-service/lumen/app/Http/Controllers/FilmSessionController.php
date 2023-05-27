<?php

namespace App\Http\Controllers;

use App\Exceptions\CinemaNotFoundException;
use App\Exceptions\FilmSessionNoEmptySeatsException;
use App\Exceptions\FilmSessionNotFoundException;
use App\Services\FilmSessionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class FilmSessionController extends Controller
{
    private FilmSessionService $filmSessionService;

    public function __construct(FilmSessionService $filmSessionService)
    {
        $this->filmSessionService = $filmSessionService;
    }

    public function filmSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cinema_uid' => 'required|uuid',
            'film_uid' => 'required|uuid',
            'date' => 'required|date_format:Y-m-d\TH:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => implode(' ', $validator->errors()->all())], Response::HTTP_BAD_REQUEST);
        }

        $cinemaUid = $request->query('cinema_uid');
        $filmUid = $request->query('film_uid');
        $dateString = $request->query('date');

        try {
            return response()->json($this->filmSessionService->getFilmSession($cinemaUid, $filmUid, $dateString)->toArray());
        } catch (CinemaNotFoundException|FilmSessionNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    public function bookSeat(string $sessionUid)
    {
        try {
            $this->filmSessionService->bookSeat($sessionUid);
        } catch (FilmSessionNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (FilmSessionNoEmptySeatsException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_CONFLICT);
        }

        return response('', Response::HTTP_NO_CONTENT);
    }
}
