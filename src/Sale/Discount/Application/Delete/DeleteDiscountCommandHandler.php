<?php

namespace App\Sale\Discount\Application\Delete;

use App\Shared\Application\Security\AuthorizationContext;
use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Reference\Event\Domain\EventId;

class DeleteDiscountCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private DiscountDeleter $deleter)
    {
    }

    public function __invoke(DeleteDiscountCommand $command): void
    {
        $this->authorization->requireAllPermissions();

        $this->deleter->__invoke(
            DiscountId::fromString($command->id()),
            EventId::fromString($command->event()),
            CompanyId::fromString($this->authorization->requireCompanyId()),
        );
    }
}
