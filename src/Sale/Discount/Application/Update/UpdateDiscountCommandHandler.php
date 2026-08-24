<?php

namespace App\Sale\Discount\Application\Update;

use App\Sale\Discount\Domain\DiscountActive;
use App\Sale\Discount\Domain\DiscountCode;
use App\Sale\Discount\Domain\DiscountEndDate;
use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Discount\Domain\DiscountStartDate;
use App\Sale\Discount\Domain\DiscountType;
use App\Sale\Discount\Domain\DiscountValue;
use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Event\Domain\EventId;

class UpdateDiscountCommandHandler
{
    public function __construct(private DiscountUpdater $updater)
    {
    }

    public function __invoke(UpdateDiscountCommand $command): void
    {
        $this->updater->__invoke(
            DiscountId::fromString($command->id()),
            DiscountActive::fromBool($command->active()),
            DiscountCode::fromString($command->code()),
            DiscountStartDate::fromString($command->startDate()),
            DiscountEndDate::fromString($command->endDate()),
            DiscountType::fromInt($command->type()),
            DiscountValue::fromFloat($command->value()),
            EventId::fromString($command->event()),
            CompanyId::fromString($command->session()->company()),
            $command->usageLimit(),
        );
    }
}
