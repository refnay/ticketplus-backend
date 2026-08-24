<?php

namespace App\Sale\Discount\Application\Find;

use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Event\Domain\EventId;

class FindDiscountQueryHandler
{
    public function __construct(private DiscountFinder $finder)
    {
    }

    public function __invoke(FindDiscountQuery $query): DiscountResponse
    {
        return $this->finder->__invoke(
            DiscountId::fromString($query->id()),
            EventId::fromString($query->event()),
        );
    }
}
