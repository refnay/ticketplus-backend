<?php

namespace App\Sale\Order\Application\Create;

use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Discount\Domain\Services\DiscountFinder;
use App\Sale\Order\Domain\Order;
use App\Sale\Order\Domain\OrderCurrency;
use App\Sale\Order\Domain\OrderSubTotal;
use App\Sale\Order\Domain\OrderTax;
use App\Sale\Order\Domain\OrderTotal;
use App\Sale\Shared\Domain\EventDayId;
use App\Sale\Shared\Domain\EventId;
use App\Sale\Shared\Domain\Exceptions\SeatNotAvailable;
use App\Sale\Shared\Domain\Exceptions\ZoneQuantityExceeded;
use App\Sale\Shared\Domain\Exceptions\ZoneQuantitySoldOut;
use App\Sale\Shared\Domain\SeatId;
use App\Sale\Shared\Domain\SeatStatusList;
use App\Sale\Shared\Domain\Services\SeatFinder;
use App\Sale\Shared\Domain\Services\ZoneFinder;
use App\Sale\Shared\Domain\UserId;
use App\Sale\Shared\Domain\ZoneId;

class OrderCreator
{
    public function __construct(
        private ZoneFinder $zoneFinder,
        private SeatFinder $seatFinder,
        private DiscountFinder $discountFinder
    ) {
    }

    public function __invoke(
        EventId $eventId,
        EventDayId $dayId,
        ZoneId $zoneId,
        ?DiscountId $discountId,
        UserId $userId,
        int $quantity,
        ?array $seatIds
    ): string {
        $zone = $this->zoneFinder->__invoke($zoneId, $eventId, $dayId);
        
        if ($zone->quantity() === 0) {
            throw new ZoneQuantitySoldOut();
        } else if ($quantity > $zone->quantity()) {
            throw new ZoneQuantityExceeded();
        }

        if (!is_null($discountId)) {
            $this->discountFinder->__invoke($discountId, $eventId);
        }

        $subTotal = $tax = $total = 0.00;

        if ($zone->numberedSeating()) {
            foreach ($seatIds as $seatId) {
                $seat = $this->seatFinder->__invoke(SeatId::fromString($seatId), $zoneId);
                if (!SeatStatusList::AVAILABLE->sameValue($seat->status())) {
                    throw new SeatNotAvailable();
                }
            }
        }

        $subTotal = $zone->price() * $quantity;
        $tax = $subTotal * $zone->taxRate();
        $total = $subTotal + $tax;
        
        $order = Order::create(
            OrderCurrency::fromString($zone->currency()),
            OrderSubTotal::fromFloat($subTotal),
            OrderTax::fromFloat($tax),
            OrderTotal::fromFloat($total),
            $userId,
        );
        $order->changeDiscountId($discountId);

        return '';
    }
}