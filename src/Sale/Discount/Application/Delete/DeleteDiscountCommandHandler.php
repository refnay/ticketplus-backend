<?php

namespace App\Sale\Discount\Application\Delete;

use App\Shared\Application\Security\AuthorizationContext;
use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Shared\Domain\CompanyId;

class DeleteDiscountCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private DiscountDeleter $deleter)
    {
    }

    public function __invoke(DeleteDiscountCommand $command): void
    {
        $companyId = $this->authorization->companyId();

        $this->deleter->__invoke(
            DiscountId::fromString($command->id()),
            CompanyId::fromString($companyId),
        );
    }
}
