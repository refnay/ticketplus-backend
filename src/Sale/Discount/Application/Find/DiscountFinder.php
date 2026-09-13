<?php

namespace App\Sale\Discount\Application\Find;

use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Discount\Domain\Services\DiscountByCompanyFinder;
use App\Sale\Shared\Domain\CompanyId;

class DiscountFinder
{
    public function __construct(private DiscountByCompanyFinder $finder) {}

    public function __invoke(DiscountId $id, CompanyId $companyId): DiscountResponse
    {
        return DiscountResponse::create($this->finder->__invoke($id, $companyId));
    }
}
