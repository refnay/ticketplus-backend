<?php

namespace App\Sale\Discount\Application\Create;

use App\Sale\Discount\Domain\Discount;
use App\Sale\Discount\Domain\DiscountActive;
use App\Sale\Discount\Domain\DiscountCode;
use App\Sale\Discount\Domain\DiscountEndDate;
use App\Sale\Discount\Domain\DiscountRepository;
use App\Sale\Discount\Domain\DiscountStartDate;
use App\Sale\Discount\Domain\DiscountType;
use App\Sale\Discount\Domain\DiscountUsage;
use App\Sale\Discount\Domain\DiscountValue;
use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Event\Domain\EventId;
use App\Sale\Event\Domain\Services\EventFinder;

class DiscountCreator
{
    public function __construct(private DiscountRepository $repository, private EventFinder $eventFinder)
    {
    }

    public function __invoke(
        DiscountActive $active,
        DiscountCode $code,
        DiscountStartDate $startDate,
        DiscountEndDate $endDate,
        DiscountType $type,
        DiscountUsage $usage,
        DiscountValue $value,
        EventId $eventId,
        CompanyId $companyId,
    ): string {
        $this->eventFinder->__invoke($eventId, $companyId);

        $discount = Discount::create(
            $active,
            $code,
            $startDate,
            $endDate,
            $type,
            $usage,
            $value,
            $eventId,
        );

        $this->repository->save($discount);

        return $discount->id()->value();
    }
}
