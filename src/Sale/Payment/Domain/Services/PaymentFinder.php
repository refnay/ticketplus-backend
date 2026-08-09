<?php

namespace App\Sale\Payment\Domain\Services;

use App\Sale\Payment\Domain\Exceptions\PaymentNotFound;
use App\Sale\Payment\Domain\Payment;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Payment\Domain\PaymentRepository;
use App\Sale\Purchase\Domain\PurchaseId;

class PaymentFinder
{
    public function __construct(private PaymentRepository $repository)
    {
    }

    public function __invoke(PaymentId $id, PurchaseId $purchaseId): Payment
    {
        $payment = $this->repository->findById($id, $purchaseId);

        if (is_null($payment)) {
            throw new PaymentNotFound();
        }

        return $payment;
    }
}