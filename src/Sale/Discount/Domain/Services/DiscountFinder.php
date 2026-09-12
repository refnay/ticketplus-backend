<?php

namespace App\Sale\Discount\Domain\Services;

use App\Sale\Discount\Domain\Discount;
use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Discount\Domain\DiscountRepository;
use App\Sale\Discount\Domain\Exceptions\DiscountNotFound;

class DiscountFinder
{
    public function __construct(private DiscountRepository $repository)
    {
    }

    public function __invoke(DiscountId $id): Discount
    {
        $discount = $this->repository->find($id);

        if (is_null($discount)) {
            throw new DiscountNotFound();
        }

        return $discount;
    }
}
