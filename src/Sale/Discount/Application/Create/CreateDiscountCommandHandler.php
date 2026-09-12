<?php

namespace App\Sale\Discount\Application\Create;

use App\Shared\Application\Security\AuthorizationContext;
use App\Sale\Discount\Domain\DiscountActive;
use App\Sale\Discount\Domain\DiscountCode;
use App\Sale\Discount\Domain\DiscountEndDate;
use App\Sale\Discount\Domain\DiscountStartDate;
use App\Sale\Discount\Domain\DiscountType;
use App\Sale\Discount\Domain\DiscountUsage;
use App\Sale\Discount\Domain\DiscountValue;
use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Reference\Event\Domain\EventId;

class CreateDiscountCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private DiscountCreator $creator)
    {
    }

    public function __invoke(CreateDiscountCommand $command): string
    {
        $companyId = $this->authorization->companyId();

        return $this->creator->__invoke(
            DiscountActive::fromBool($command->active()),
            DiscountCode::fromString($command->code()),
            DiscountStartDate::fromString($command->startDate()),
            DiscountEndDate::fromString($command->endDate()),
            DiscountType::fromInt($command->type()),
            DiscountUsage::fromLimit($command->usageLimit()),
            DiscountValue::fromFloat($command->value()),
            EventId::fromString($command->event()),
            CompanyId::fromString($companyId),
        );
    }
}
