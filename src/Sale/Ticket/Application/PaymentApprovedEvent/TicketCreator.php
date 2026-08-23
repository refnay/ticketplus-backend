<?php

namespace App\Sale\Ticket\Application\PaymentApprovedEvent;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Shared\Domain\SeatId;
use App\Sale\Shared\Domain\UserId;
use App\Sale\Ticket\Application\Create\TicketCreator as ServiceTicketCreator;
use App\Shared\Infrastructure\Persistence\Doctrine\TransactionManager;
use Throwable;

class TicketCreator
{
    public function __construct(
        private OrderFinder $orderFinder,
        private TransactionManager $transaction,
        private ServiceTicketCreator $creator,
    ) {
    }

    public function __invoke(OrderId $orderId, UserId $userId): void
    {
        $order = $this->orderFinder->__invoke($orderId, $userId);

        $this->transaction->begin();
        try {
            foreach ($order->details()->items() as $item) {
                $seatIds = $item['seats'];

                if (is_array($seatIds) && count($seatIds) > 0) {
                    foreach ($seatIds as $seatId) {
                        $this->creator->__invoke($order, $item, SeatId::fromString($seatId));
                    }
                } else {
                    for ($i = 0; $i < $item['quantity']; $i++) {
                        $this->creator->__invoke($order, $item, null);
                    }
                }
            }
            $this->transaction->commit();
        } catch (Throwable) {
            $this->transaction->rollback();
        }
    }
}
