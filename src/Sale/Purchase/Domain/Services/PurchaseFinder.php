<?php

namespace App\Sale\Purchase\Domain\Services;

use App\Sale\Purchase\Domain\Exceptions\PurchaseNotFound;
use App\Sale\Purchase\Domain\Purchase;
use App\Sale\Purchase\Domain\PurchaseId;
use App\Sale\Purchase\Domain\PurchaseRepository;
use App\Sale\Purchase\Domain\UserId;

class PurchaseFinder
{
    public function __construct(private PurchaseRepository $repository)
    {
    }

    public function __invoke(PurchaseId $id, UserId $userId): Purchase
    {
        $purchase = $this->repository->findById($id, $userId);

        if (is_null($purchase)) {
            throw new PurchaseNotFound();
        }

        return $purchase;
    }
}