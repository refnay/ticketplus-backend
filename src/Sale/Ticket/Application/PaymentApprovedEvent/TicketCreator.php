<?php

namespace App\Sale\Ticket\Application\PaymentApprovedEvent;

use App\Sale\Order\Domain\Order;
use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Payment\Domain\Payment;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Payment\Domain\Services\PaymentFinder;
use App\Sale\Shared\Domain\SeatId;
use App\Sale\Shared\Domain\UserId;
use App\Sale\Shared\Domain\ZoneId;
use App\Sale\Ticket\Application\Create\TicketCreator as ServiceTicketCreator;
use App\Shared\Infrastructure\Persistence\Doctrine\TransactionManager;
use Throwable;

class TicketCreator
{
    public function __construct(
        private PaymentFinder $paymentFinder,
        private OrderFinder $orderFinder,
        private TransactionManager $transaction,
        private ServiceTicketCreator $creator,

    ) {}

    public function __invoke(OrderId $orderId, UserId $userId): void
    {
        $order = $this->orderFinder->__invoke($orderId, $userId);

        $details = $order->details();
        $seatIds = $details->seats();

        if (is_null($seatIds)) {
            $this->zoneHandler($order);
        } else {
            $this->seatHandler($order);
        }
    }

    private function zoneHandler(Order $order): void
    {
        $details = $order->details();

        $this->transaction->begin();
        try {
            for ($i = 0; $i < $details->quantity(); $i++) {
                $this->creator->__invoke($order, ZoneId::fromString($details->zone()), null);
            }
            $this->transaction->commit();
        } catch (Throwable) {
            $this->transaction->rollback();
        }
    }

    private function seatHandler(Order $order): void
    {
        $details = $order->details();

        $this->transaction->begin();
        try {
            foreach ($details->seats() as $seatId) {
                $this->creator->__invoke($order, ZoneId::fromString($details->zone()), SeatId::fromString($seatId));
            }
            $this->transaction->commit();
        } catch (Throwable) {
            $this->transaction->rollback();
        }
    }
}
