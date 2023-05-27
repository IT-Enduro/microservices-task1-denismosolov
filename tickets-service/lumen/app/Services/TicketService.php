<?php

namespace App\Services;

use App\Enums\TicketStatusEnum;
use App\Exceptions\CouldNotBookFilmSessionException;
use App\Exceptions\CouldNotFindFilmSessionException;
use App\Exceptions\CouldNotFindTicketException;
use App\Exceptions\CouldNotReturnTicketException;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Carbon;

class TicketService
{
    private FilmSessionService $filmSessionService;

    public function __construct(FilmSessionService $filmSessionService)
    {
        $this->filmSessionService = $filmSessionService;
    }

    /**
     * @param string $cinemaUid
     * @param string $filmUid
     * @param string $dateString
     * @param int $row
     * @param int $seat
     * @param string $userName
     * @return Ticket
     * @throws CouldNotFindFilmSessionException
     * @throws CouldNotBookFilmSessionException
     */
    public function book(string $cinemaUid, string $filmUid, string $dateString, int $row, int $seat, string $userName): Ticket
    {
        $sessionUId = $this->filmSessionService->getFilmSessionUid($cinemaUid, $filmUid, $dateString);
        $this->filmSessionService->book($sessionUId);
        // @todo rollback on failure?

        $ticket = new Ticket();
        $ticket->ticket_uid = Uuid::uuid4()->toString();
        $ticket->film_uid = $filmUid;
        $ticket->session_uid = $sessionUId;
        $ticket->user_name = $userName;
        $ticket->date = $dateString;
        $ticket->status = TicketStatusEnum::BOOKED;
        $ticket->row = $row;
        $ticket->seat = $seat;
        $ticket->save();

        return $ticket;
    }

    public function getTicket(string $ticketUid): Ticket
    {
        try {
            return Ticket::where('ticket_uid', $ticketUid)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new CouldNotFindTicketException('Could not find ticket.', 0, $e);
        }
    }

    public function getTicketUrl(string $ticketUid): string
    {
        return sprintf('%s/api/v1/tickets/%s', env('APP_URL'), $ticketUid);
    }

    public function returnTicket(string $ticketUid, Carbon $now): Ticket
    {
        try {
            $ticket = Ticket::where('ticket_uid', $ticketUid)->firstOrFail();
            if ($now->gt($ticket->date->subHour())) {
                throw new CouldNotReturnTicketException('Too late.');
            }
            $ticket->status = TicketStatusEnum::CANCELED;
            $ticket->save();

            return $ticket;
        } catch (ModelNotFoundException $e) {
            throw new CouldNotFindTicketException('Could not find ticket.', 0, $e);
        }
    }
}
