<?php

namespace App\Sale\Discount\Application\Update;

use App\Sale\Discount\Domain\DiscountActive;
use App\Sale\Discount\Domain\DiscountCode;
use App\Sale\Discount\Domain\DiscountEndDate;
use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Discount\Domain\DiscountRepository;
use App\Sale\Discount\Domain\DiscountStartDate;
use App\Sale\Discount\Domain\DiscountType;
use App\Sale\Discount\Domain\DiscountUsage;
use App\Sale\Discount\Domain\DiscountValue;
use App\Sale\Discount\Domain\Services\CompanyDiscountFinder;
use App\Sale\Shared\Domain\CompanyId;

class DiscountUpdater
{
    public function __construct(
        private DiscountRepository $repository,
        private CompanyDiscountFinder $discountFinder,
    ) {
    }

    public function __invoke(
        DiscountId $id,
        DiscountActive $active,
        DiscountCode $code,
        DiscountStartDate $startDate,
        DiscountEndDate $endDate,
        DiscountType $type,
        DiscountValue $value,
        CompanyId $companyId,
        int $usageLimit,
    ): void {
        $discount = $this->discountFinder->__invoke($id, $companyId);
        $discount->changeActive($active);
        $discount->changeCode($code);
        $discount->changeStartDate($startDate);
        $discount->changeEndDate($endDate);
        $discount->changeType($type);
        $discount->changeUsage(DiscountUsage::create($usageLimit, $discount->usage()->count()));
        $discount->changeValue($value);

        $this->repository->update($discount);
    }
}
