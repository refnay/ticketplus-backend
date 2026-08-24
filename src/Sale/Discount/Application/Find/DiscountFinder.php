<?php

namespace App\Sale\Discount\Application\Find;

use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Discount\Domain\Services\DiscountFinder as DomainDiscountFinder;
use App\Sale\Reference\Event\Domain\EventId;

class DiscountFinder
{
    public function __construct(private DomainDiscountFinder $finder)
    {
    }

    public function __invoke(DiscountId $id, EventId $eventId): DiscountResponse
    {
        return DiscountResponse::create($this->finder->__invoke($id, $eventId));
    }
}
