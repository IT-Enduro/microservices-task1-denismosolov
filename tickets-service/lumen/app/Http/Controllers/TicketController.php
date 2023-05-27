<?php

namespace App\Http\Controllers;

use App\Exceptions\CouldNotBookFilmSessionException;
use App\Exceptions\CouldNotFindFilmSessionException;
use App\Exceptions\CouldNotFindTicketException;
use App\Exceptions\CouldNotReturnTicketException;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Carbon;

class TicketController extends Controller
{
    private TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function bookTicket(Request $request, string $cinemaUid, string $filmUid)
    {
        $userName = $request->header('X-User-Name', '');
        // @todo validate request params
        $dateString = $request->json('date');
        $row = $request->json('row');
        $seat = $request->json('seat');

        try {
            $ticket = $this->ticketService->book($cinemaUid, $filmUid, $dateString, $row, $seat, $userName);

            return response('', Response::HTTP_CREATED)->header('Location', $this->ticketService->getTicketUrl($ticket->ticket_uid));
        } catch (CouldNotFindFilmSessionException|CouldNotBookFilmSessionException $e) {
            Log::error($e->getTraceAsString());
            return response($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error($e->getTraceAsString());
            return response('', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function tickets($ticketUid)
    {
        try {
            $ticket = $this->ticketService->getTicket($ticketUid);

            return response()->json($ticket->toArray(), Response::HTTP_OK);
        } catch (CouldNotFindTicketException $e) {
            Log::error($e->getTraceAsString());
            return response(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error($e->getTraceAsString());
            return response('', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function returnTicket(string $ticketUid)
    {
        try {
            $now = Carbon::now();
            $this->ticketService->returnTicket($ticketUid, $now);

            return response('', Response::HTTP_NO_CONTENT);
        } catch (CouldNotFindTicketException $e) {
            Log::error($e->getTraceAsString());
            return response('', Response::HTTP_NOT_FOUND);
        } catch (CouldNotReturnTicketException $e) {
            return response('', Response::HTTP_CONFLICT);
        } catch (Exception $e) {
            Log::error($e->getTraceAsString());
            return response('', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
