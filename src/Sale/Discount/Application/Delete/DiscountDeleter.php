<?php

namespace App\Sale\Discount\Application\Delete;

use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Discount\Domain\DiscountRepository;
use App\Sale\Discount\Domain\Services\CompanyDiscountFinder;
use App\Sale\Shared\Domain\CompanyId;

class DiscountDeleter
{
    public function __construct(
        private DiscountRepository $repository,
        private CompanyDiscountFinder $discountFinder,
    ) {
    }

    public function __invoke(DiscountId $id, CompanyId $companyId): void
    {
        $discount = $this->discountFinder->__invoke($id, $companyId);

        $this->repository->delete($discount);
    }
}
