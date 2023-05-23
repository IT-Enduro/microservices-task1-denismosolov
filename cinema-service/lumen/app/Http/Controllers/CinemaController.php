<?php

namespace App\Http\Controllers;

use App\Exceptions\CinemaNotFoundException;
use App\Exceptions\CouldNotGetFilmsException;
use App\Exceptions\FilmSessionNotFoundException;
use App\Models\Cinema;
use App\Services\CinemaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CinemaController extends Controller
{
    private CinemaService $cinemaService;

    public function __construct(CinemaService $cinemaService)
    {
        $this->cinemaService = $cinemaService;
    }

    public function index(Request $request)
    {
        $page = $request->query('page') ?? 0;
        $size = $request->query('size') ?? 100;

        $films = Cinema::paginate($size, ['*'], 'page', $page);

        return response()->json($films);
    }

    public function films($cinemaUid)
    {
        try {
            $filmsInCinemaResponse = $this->cinemaService->getFilmsInCinemaResponse($cinemaUid);

            return response()->json($filmsInCinemaResponse);
        } catch (CinemaNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (CouldNotGetFilmsException $e) {
            Log::error($e->getPrevious()?->getTraceAsString());
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_GATEWAY);
        }
    }

    public function bookSeat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cinema_uid' => 'required|uuid',
            'film_uid' => 'required|uuid',
            'date' => 'required|date_format:Y-m-d\TH:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $cinemaUid = $request->query('cinema_uid');
        $filmUid = $request->query('film_uid');
        $dateString = $request->query('date');

        try {
            $this->cinemaService->bookSeat($cinemaUid, $filmUid, $dateString);
        } catch (CinemaNotFoundException|FilmSessionNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return response('', Response::HTTP_NO_CONTENT);
    }
}
