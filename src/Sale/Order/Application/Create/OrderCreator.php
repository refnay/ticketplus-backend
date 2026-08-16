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
use App\Sale\Shared\Domain\EventDayId;
use App\Sale\Shared\Domain\EventId;
use App\Sale\Shared\Domain\Exceptions\SeatNotAvailable;
use App\Sale\Shared\Domain\Exceptions\SeatNotFound;
use App\Sale\Shared\Domain\Exceptions\ZoneNotFound;
use App\Sale\Shared\Domain\Exceptions\ZoneQuantityExceeded;
use App\Sale\Shared\Domain\Exceptions\ZoneQuantitySoldOut;
use App\Sale\Shared\Domain\SeatId;
use App\Sale\Shared\Domain\SeatStatusList;
use App\Sale\Shared\Domain\Services\EventDayFinder;
use App\Sale\Shared\Domain\Services\SeatFinder;
use App\Sale\Shared\Domain\Services\ZoneFinder;
use App\Sale\Shared\Domain\UserId;
use App\Sale\Shared\Domain\ZoneId;
use App\Shared\Application\Messenger\EventBus;
use App\Shared\Domain\Utils\Primitive\ArrayBuilder;

class OrderCreator
{
    private ArrayBuilder $events;
    
    public function __construct(
        private EventDayFinder $dayFinder,
        private ZoneFinder $zoneFinder,
        private SeatFinder $seatFinder,
        private DiscountFinder $discountFinder,
        private DiscountApply $applyDiscount,
        private OrderRepository $repository,
        private EventBus $eventBus,
    ) {
        $this->events = ArrayBuilder::generate();
    }

    public function __invoke(
        EventId $eventId,
        EventDayId $dayId,
        ?DiscountId $discountId,
        UserId $userId,
        array $zones,
    ): string {
        if (!(count($zones) > 0)) {
            throw new ZoneNotFound();
        }

        $day = $this->dayFinder->__invoke($eventId, $dayId);
        $currency = $day->currency();
        $discount = null;

        if (!is_null($discountId)) {
            $discount = $this->discountFinder->__invoke($discountId, $eventId);
        }

        $details = [];
        $price = 0.00;
        $subTotal = 0.00;
        $tax = 0.00;
        $total = 0.00;

        /** @var ZoneCommand $zoneCommand */
        foreach ($zones as $zoneCommand) {
            $zoneId = ZoneId::fromString($zoneCommand->id());
            $quantity = $zoneCommand->quantity();
            $seatIds = $zoneCommand->seatIds();

            $zone = $this->zoneFinder->__invoke($zoneId, $eventId, $dayId);

            if ($zone->quantity() === 0) {
                throw new ZoneQuantitySoldOut();
            } else if ($quantity > $zone->quantity()) {
                throw new ZoneQuantityExceeded();
            }

            if ($zone->numberedSeating()) {
                if (is_null($seatIds)) {
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
                'zone' => $zone->id(),
                'quantity' => $quantity,
                'seats' => $seatIds,
                'price' => $zone->price(),
            ];
        }

        $subTotal = !is_null($discount) ? $this->applyDiscount->__invoke($price, $discount) : $price;
        $tax = $subTotal * $day->taxRate() / 100;
        $total = $subTotal + $tax;

        $order = Order::create(
            OrderCurrency::fromString($currency),
            OrderPrice::fromFloat($price),
            OrderSubTotal::fromFloat($subTotal),
            OrderTax::fromFloat($tax),
            OrderTotal::fromFloat($total),
            OrderDetails::fromPattern($eventId->value(), $dayId->value(), $details),
            $userId,
        );
        $order->changeDiscountId($discountId);

        $this->repository->save($order);

        $this->events->add(new OrderProcessedDomainEvent(
            $eventId->value(),
            $dayId->value(),
            $details,
            $order->status()->value(),
        ));
        $this->eventBus->dispatch(...$this->events->items());

        return $order->id()->value();
    }
}
