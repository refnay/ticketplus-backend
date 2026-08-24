<?php

namespace App\Sale\Discount\Application\Update;

use App\Shared\Application\Security\AuthorizationContext;
use App\Sale\Discount\Domain\DiscountActive;
use App\Sale\Discount\Domain\DiscountCode;
use App\Sale\Discount\Domain\DiscountEndDate;
use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Discount\Domain\DiscountStartDate;
use App\Sale\Discount\Domain\DiscountType;
use App\Sale\Discount\Domain\DiscountValue;
use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Reference\Event\Domain\EventId;

class UpdateDiscountCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private DiscountUpdater $updater)
    {
    }

    public function __invoke(UpdateDiscountCommand $command): void
    {
        $this->authorization->requireAllPermissions();

        $this->updater->__invoke(
            DiscountId::fromString($command->id()),
            DiscountActive::fromBool($command->active()),
            DiscountCode::fromString($command->code()),
            DiscountStartDate::fromString($command->startDate()),
            DiscountEndDate::fromString($command->endDate()),
            DiscountType::fromInt($command->type()),
            DiscountValue::fromFloat($command->value()),
            EventId::fromString($command->event()),
            CompanyId::fromString($this->authorization->requireCompanyId()),
            $command->usageLimit(),
        );
    }
}
