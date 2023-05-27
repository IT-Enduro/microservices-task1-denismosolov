<?php

namespace App\Http\Controllers;

use App\Exceptions\CinemaNotFoundException;
use App\Exceptions\CouldNotGetFilmsException;
use App\Models\Cinema;
use App\Services\CinemaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Exception;

class CinemaController extends Controller
{
    private CinemaService $cinemaService;

    public function __construct(CinemaService $cinemaService)
    {
        $this->cinemaService = $cinemaService;
    }

    public function index(Request $request)
    {
        try {
            $page = $request->query('page') ?? 0;
            $size = $request->query('size') ?? 100;

            $films = Cinema::paginate($size, ['*'], 'page', $page);

            return response()->json($films);
        } catch (Exception $e) {
            Log::error($e->getTraceAsString());
            return response('', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function films($cinemaUid)
    {
        try {
            $filmsInCinemaResponse = $this->cinemaService->getFilmsInCinemaResponse($cinemaUid);

            return response()->json($filmsInCinemaResponse);
        } catch (CinemaNotFoundException $e) {
            Log::error($e->getTraceAsString());
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (CouldNotGetFilmsException $e) {
            Log::error($e->getTraceAsString());
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_GATEWAY);
        } catch (Exception $e) {
            Log::error($e->getTraceAsString());
            return response('', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
