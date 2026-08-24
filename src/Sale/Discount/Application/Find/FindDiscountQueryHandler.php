<?php

namespace App\Sale\Discount\Application\Find;

use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Reference\Event\Domain\EventId;
use App\Shared\Application\Security\AuthorizationContext;

class FindDiscountQueryHandler
{
    public function __construct(
        private DiscountFinder $finder,
        private AuthorizationContext $authorization,
    ) {
    }

    public function __invoke(FindDiscountQuery $query): DiscountResponse
    {
        $this->authorization->requireAllPermissions();

        return $this->finder->__invoke(
            DiscountId::fromString($query->id()),
            EventId::fromString($query->event()),
        );
    }
}
