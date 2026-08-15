<?php

namespace App\Sale\Order\Application\Create;

use App\Sale\Shared\Domain\EventDayId;
use App\Sale\Shared\Domain\EventId;
use App\Sale\Shared\Domain\ZoneId;

class OrderCreator
{
    public function __construct()
    {
    }

    public function __invoke(
        EventId $eventId,
        EventDayId $dayId,
        ZoneId $zoneId,
        int $quantity,
        ?array $seats
    ): string {
        return '';
    }
}