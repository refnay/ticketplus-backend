<?php

namespace App\Sale\Order\Application\Create;

use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Discount\Domain\Services\DiscountApply;
use App\Sale\Discount\Domain\Services\DiscountFinder;
use App\Sale\Order\Domain\Events\OrderProcessedDomainEvent;
use App\Sale\Order\Domain\Order;
use App\Sale\Order\Domain\OrderCurrency;
use App\Sale\Order\Domain\OrderDetails;
use App\Sale\Order\Domain\OrderPrice;
use App\Sale\Order\Domain\OrderRepository;
use App\Sale\Order\Domain\OrderSubTotal;
use App\Sale\Order\Domain\OrderTax;
use App\Sale\Order\Domain\OrderTotal;
use App\Sale\Reference\EventDay\Domain\EventDayId;
use App\Sale\Reference\Event\Domain\EventId;
use App\Sale\Reference\Seat\Domain\Exceptions\SeatNotAvailable;
use App\Sale\Reference\Seat\Domain\Exceptions\SeatNotFound;
use App\Sale\Reference\Zone\Domain\Exceptions\ZoneNotFound;
use App\Sale\Reference\Zone\Domain\Exceptions\ZoneQuantityExceeded;
use App\Sale\Reference\Zone\Domain\Exceptions\ZoneQuantitySoldOut;
use App\Sale\Reference\Seat\Domain\SeatId;
use App\Sale\Reference\Seat\Domain\SeatStatusList;
use App\Sale\Reference\Event\Domain\Services\EventFinder;
use App\Sale\Reference\Seat\Domain\Services\SeatFinder;
use App\Sale\Reference\Zone\Domain\Services\ZoneFinder;
use App\Sale\Reference\User\Domain\UserId;
use App\Sale\Reference\Zone\Domain\ZoneId;
use App\Shared\Application\Bus\EventBus;

class OrderCreator
{
    public function __construct(
        private EventFinder $eventFinder,
        private ZoneFinder $zoneFinder,
        private SeatFinder $seatFinder,
        private DiscountFinder $discountFinder,
        private DiscountApply $applyDiscount,
        private OrderRepository $repository,
        private EventBus $eventBus,
    ) {}

    public function __invoke(
        EventId $eventId,
        EventDayId $dayId,
        ?DiscountId $discountId,
        UserId $userId,
        array $items,
    ): string {
        if (!(count($items) > 0)) {
            throw new ZoneNotFound();
        }

        $event = $this->eventFinder->__invoke($eventId);
        $discount = null;

        if (!is_null($discountId)) {
            $discount = $this->discountFinder->__invoke($discountId, $eventId);
        }

        $details = [];
        $price = $subTotal = $tax = $total = 0.00;

        /** @var OrderItemCommand $item */
        foreach ($items as $item) {
            $zoneId = ZoneId::fromString($item->zone());
            $quantity = $item->quantity();
            $seatIds = $item->seats();

            $zone = $this->zoneFinder->__invoke($zoneId, $eventId, $dayId);

            if ($zone->quantity() === 0) {
                throw new ZoneQuantitySoldOut();
            } else if ($quantity > $zone->quantity()) {
                throw new ZoneQuantityExceeded();
            }

            if ($zone->numberedSeating()) {
                if (!(is_array($seatIds) && count($seatIds) > 0)) {
                    throw new SeatNotFound();
                }

                foreach ($seatIds as $seatId) {
                    $seat = $this->seatFinder->__invoke(SeatId::fromString($seatId), $zoneId);
                    if (!SeatStatusList::AVAILABLE->sameValue($seat->status())) {
                        throw new SeatNotAvailable();
                    }
                }
            }

            $price += $zone->price() * $quantity;
            $details[] = [
                'zone' => $zoneId,
                'quantity' => $quantity,
                'seats' => $seatIds,
                'price' => $zone->price() * $event->taxRate() / 100,
            ];
        }

        $subTotal = !is_null($discount) ? $this->applyDiscount->__invoke($price, $discount) : $price;
        $tax = $subTotal * $event->taxRate() / 100;
        $total = $subTotal + $tax;

        $order = Order::create(
            OrderCurrency::fromString($event->currency()),
            OrderPrice::fromFloat($price),
            OrderSubTotal::fromFloat($subTotal),
            OrderTax::fromFloat($tax),
            OrderTotal::fromFloat($total),
            OrderDetails::fromPattern($eventId->value(), $dayId->value(), $details),
            $eventId,
            $userId,
        );
        $order->changeDiscountId($discountId);

        $this->repository->save($order);

        $this->eventBus->publish(new OrderProcessedDomainEvent(
            $eventId->value(),
            $dayId->value(),
            $details,
            $order->status()->value(),
        ));

        return $order->id()->value();
    }
}
