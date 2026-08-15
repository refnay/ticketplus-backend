<?php

namespace App\Sale\Order\Application\Create;

use App\Sale\Order\Domain\Order;
use App\Sale\Order\Domain\OrderCurrency;
use App\Sale\Order\Domain\OrderExpiresAt;
use App\Sale\Order\Domain\OrderSubTotal;
use App\Sale\Order\Domain\OrderTax;
use App\Sale\Order\Domain\OrderTotal;
use App\Sale\Shared\Domain\EventDayId;
use App\Sale\Shared\Domain\EventId;
use App\Sale\Shared\Domain\Services\ZoneFinder;
use App\Sale\Shared\Domain\UserId;
use App\Sale\Shared\Domain\ZoneId;

class OrderCreator
{
    public function __construct(private ZoneFinder $zoneFinder)
    {
    }

    public function __invoke(
        EventId $eventId,
        EventDayId $dayId,
        ZoneId $zoneId,
        UserId $userId,
        int $quantity,
        ?array $seats
    ): string {
        $zone = $this->zoneFinder->__invoke($zoneId, $eventId, $dayId);
        
        $order = Order::create(
            OrderCurrency::fromString($zone->currency()),
            OrderSubTotal::fromZero(),
            OrderTax::fromZero(),
            OrderTotal::fromZero(),
            OrderExpiresAt::now(),
            $userId,
        );

        return '';
    }
}