<?php

namespace App\Sale\Ticket\Application\Create;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Payment\Domain\Services\PaymentFinder;
use App\Sale\Shared\Domain\EventDayId;
use App\Sale\Shared\Domain\EventId;
use App\Sale\Shared\Domain\Exceptions\SeatNotFound;
use App\Sale\Shared\Domain\SeatId;
use App\Sale\Shared\Domain\Services\EventDayFinder;
use App\Sale\Shared\Domain\Services\SeatFinder;
use App\Sale\Shared\Domain\Services\ZoneFinder;
use App\Sale\Shared\Domain\UserId;
use App\Sale\Shared\Domain\ZoneId;
use App\Sale\Ticket\Domain\Ticket;
use App\Sale\Ticket\Domain\TicketInformation;
use App\Sale\Ticket\Domain\TicketPrice;
use App\Sale\Ticket\Domain\TicketRepository;
use DateTimeImmutable;

class TicketCreator
{
    public function __construct(
        private PaymentFinder $paymentFinder,
        private OrderFinder $orderFinder,
        private EventDayFinder $dayFinder,
        private TicketRepository $repository,
        private ZoneFinder $zoneFinder,
        private SeatFinder $seatFinder,
    ) {
    }

    public function __invoke(
        PaymentId $paymentId,
        OrderId $orderId,
        UserId $userId,
        ZoneId $zoneId,
        ?SeatId $seatId
    ): void {
        $order = $this->orderFinder->__invoke($orderId, $userId);
        $payment = $this->paymentFinder->__invoke($paymentId, $orderId);

        if ($payment->status()->isApproved()) {
            return;
        }
        
        $details = $order->details();

        $seat = null;
        $day = $this->dayFinder->__invoke(
            EventId::fromString($details->event()),
            EventDayId::fromString($details->day()),
        );
        $zone = $this->zoneFinder->__invoke(
            ZoneId::fromString($zoneId),
            EventId::fromString($details->event()),
            EventDayId::fromString($details->day()),
        );

        try {
            $seat = $this->seatFinder->__invoke($seatId, $zoneId);
        } catch (SeatNotFound) {
        }

        $date = DateTimeImmutable::createFromFormat('Y-m-d', $day->date());
        $eventName = $day->eventName();
        $zoneName = $zone->name();
        $seatCode = is_null($seat) ? null : $seat->code();
        $price = $order->price()->value() / $details->quantity();

        $ticket = Ticket::create(
            TicketInformation::create($date, $eventName, $zoneName, $seatCode),
            TicketPrice::fromFloat($price),
            $orderId,
            $zoneId,
        );
        $ticket->changeSeatId($seatId);

        $this->repository->save($ticket);
    }
}
