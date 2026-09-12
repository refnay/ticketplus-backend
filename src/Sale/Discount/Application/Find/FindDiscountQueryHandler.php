<?php

namespace App\Sale\Discount\Application\Find;

use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Shared\Domain\CompanyId;
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
        $companyId = $this->authorization->companyId();

        return $this->finder->__invoke(
            DiscountId::fromString($query->id()),
            CompanyId::fromString($companyId),
        );
    }
}
