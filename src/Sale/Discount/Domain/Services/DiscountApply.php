<?php

namespace App\Sale\Discount\Domain\Services;

use App\Sale\Discount\Domain\Discount;
use App\Sale\Discount\Domain\Exceptions\DiscountNotActive;
use App\Sale\Discount\Domain\Exceptions\DiscountUsageLimitReached;

class DiscountApply
{
    public function __invoke(float $value, Discount $discount): float
    {
        if ($discount->active()->isDisable()) {
            throw new DiscountNotActive();
        }

        if ($discount->usage()->isAvailable()) {
            throw new DiscountUsageLimitReached();
        }

        $amount = $discount->type()->isPercentage()
            ? $discount->value()->value() * $value
            : $discount->value()->value();

        return $amount > $value ? 0.00 : $value - $amount;
    }
}