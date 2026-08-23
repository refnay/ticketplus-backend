<?php

namespace App\Sale\Discount\Application\Delete;

use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Discount\Domain\DiscountRepository;
use App\Sale\Discount\Domain\Services\DiscountFinder;
use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Shared\Domain\EventId;
use App\Sale\Shared\Domain\Services\EventFinder;

class DiscountDeleter
{
    public function __construct(
        private DiscountRepository $repository,
        private DiscountFinder $discountFinder,
        private EventFinder $eventFinder,
    ) {
    }

    public function __invoke(DiscountId $id, EventId $eventId, CompanyId $companyId): void
    {
        $this->eventFinder->__invoke($eventId, $companyId);
        
        $discount = $this->discountFinder->__invoke($id, $eventId);

        $this->repository->delete($discount);
    }
}
