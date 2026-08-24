<?php

namespace App\Sale\Ticket\Application\PaymentApprovedEvent;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Seat\Domain\SeatId;
use App\Sale\User\Domain\UserId;
use App\Sale\Ticket\Application\Create\TicketCreator as ServiceTicketCreator;
use App\Sale\Ticket\Domain\Events\TicketCreatedDomainEvent;
use App\Shared\Application\Messenger\EventBus;
use App\Shared\Domain\Utils\Primitive\ArrayBuilder;
use App\Shared\Domain\Persistence\TransactionService;
use Throwable;

class TicketCreator
{
    private ArrayBuilder $events;
    
    public function __construct(
        private OrderFinder $orderFinder,
        private TransactionService $transaction,
        private ServiceTicketCreator $creator,
        private EventBus $eventBus,
    ) {
        $this->events = ArrayBuilder::generate();
    }

    public function __invoke(OrderId $orderId, UserId $userId): void
    {
        $order = $this->orderFinder->__invoke($orderId, $userId);
        $tickets = ArrayBuilder::generate();

        $this->transaction->begin();
        try {
            foreach ($order->details()->items() as $item) {
                $seatIds = $item['seats'];

                if (is_array($seatIds) && count($seatIds) > 0) {
                    foreach ($seatIds as $seatId) {
                        $id = $this->creator->__invoke($order, $item, SeatId::fromString($seatId));
                        $tickets->add($id);
                    }
                } else {
                    for ($i = 0; $i < $item['quantity']; $i++) {
                        $id = $this->creator->__invoke($order, $item, null);
                        $tickets->add($id);
                    }
                }
            }
            $this->transaction->commit();
            
            $this->events->add(new TicketCreatedDomainEvent($tickets->items(), $orderId, $userId));
            $this->eventBus->dispatch(...$this->events->items());
        } catch (Throwable) {
            $this->transaction->rollback();
        }
    }
}
