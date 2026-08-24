<?php

namespace App\Sale\Ticket\Application\Create;

use App\Sale\Order\Domain\Order;
use App\Sale\Reference\EventDay\Domain\EventDayId;
use App\Sale\Reference\Event\Domain\EventId;
use App\Sale\Reference\Seat\Domain\Exceptions\SeatNotFound;
use App\Sale\Reference\Seat\Domain\SeatId;
use App\Sale\Reference\EventDay\Domain\Services\EventDayFinder;
use App\Sale\Reference\Event\Domain\Services\EventFinder;
use App\Sale\Reference\Seat\Domain\Services\SeatFinder;
use App\Sale\Reference\Zone\Domain\Services\ZoneFinder;
use App\Sale\Reference\Zone\Domain\ZoneId;
use App\Sale\Ticket\Domain\Ticket;
use App\Sale\Ticket\Domain\TicketInformation;
use App\Sale\Ticket\Domain\TicketPrice;
use App\Sale\Ticket\Domain\TicketRepository;

class TicketCreator
{
    public function __construct(
        private EventDayFinder $dayFinder,
        private EventFinder $eventFinder,
        private TicketRepository $repository,
        private ZoneFinder $zoneFinder,
        private SeatFinder $seatFinder,
    ) {
    }

    public function __invoke(Order $order, array $item, ?SeatId $seatId): string
    {
        $details = $order->details();

        $eventId = EventId::fromString($details->event());
        $dayId = EventDayId::fromString($details->day());
        $zoneId = ZoneId::fromString($item['zone']);

        $day = $this->dayFinder->__invoke($eventId, $dayId);
        $event = $this->eventFinder->__invoke($eventId);
        $zone = $this->zoneFinder->__invoke($zoneId, $eventId, $dayId);

        $seat = null;
        try {
            $seat = $this->seatFinder->__invoke($seatId, $zoneId);
        } catch (SeatNotFound) {
        }

        $ticket = Ticket::create(
            TicketInformation::create($day->date(), $event->name(), $zone->name(), is_null($seat) ? null : $seat->code()),
            TicketPrice::fromFloat($item['price']),
            $order->id(),
            $zoneId,
        );
        $ticket->changeSeatId($seatId);

        $this->repository->save($ticket);

        return $ticket->id()->value();
    }
}
