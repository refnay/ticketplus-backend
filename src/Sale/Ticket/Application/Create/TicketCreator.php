<?php

namespace App\Sale\Ticket\Application\Create;

use App\Sale\Order\Domain\Order;
use App\Sale\Shared\Domain\EventDayId;
use App\Sale\Shared\Domain\EventId;
use App\Sale\Shared\Domain\Exceptions\SeatNotFound;
use App\Sale\Shared\Domain\SeatId;
use App\Sale\Shared\Domain\Services\EventDayFinder;
use App\Sale\Shared\Domain\Services\SeatFinder;
use App\Sale\Shared\Domain\Services\ZoneFinder;
use App\Sale\Shared\Domain\ZoneId;
use App\Sale\Ticket\Domain\Ticket;
use App\Sale\Ticket\Domain\TicketInformation;
use App\Sale\Ticket\Domain\TicketPrice;
use App\Sale\Ticket\Domain\TicketRepository;

class TicketCreator
{
    public function __construct(
        private EventDayFinder $dayFinder,
        private TicketRepository $repository,
        private ZoneFinder $zoneFinder,
        private SeatFinder $seatFinder,
    ) {
    }

    public function __invoke(
        Order $order,
        array $item,
        ?SeatId $seatId
    ): void {
        $details = $order->details();
        $zoneId = ZoneId::fromString($item['zone']);

        $day = $this->dayFinder->__invoke(
            EventId::fromString($details->event()),
            EventDayId::fromString($details->day()),
        );
        $zone = $this->zoneFinder->__invoke(
            $zoneId,
            EventId::fromString($details->event()),
            EventDayId::fromString($details->day()),
        );

        $seat = null;

        try {
            $seat = $this->seatFinder->__invoke($seatId, $zoneId);
        } catch (SeatNotFound) {
        }

        $ticket = Ticket::create(
            TicketInformation::create($day->date(), $day->eventName(), $zone->name(), is_null($seat) ? null : $seat->code()),
            TicketPrice::fromFloat($item['price']),
            $order->id(),
            $zoneId,
        );
        $ticket->changeSeatId($seatId);

        $this->repository->save($ticket);
    }
}
