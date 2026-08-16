<?php

namespace App\Sale\Payment\Domain\Services;

use App\Sale\Payment\Domain\Exceptions\PaymentNotFound;
use App\Sale\Payment\Domain\Payment;
use App\Sale\Payment\Domain\PaymentRepository;
use App\Sale\Order\Domain\OrderId;

class PaymentProcessingFinder
{
    public function __construct(private PaymentRepository $repository)
    {
    }

    public function __invoke(OrderId $orderId): Payment
    {
        $payment = $this->repository->findProcessing($orderId);

        if (is_null($payment)) {
            throw new PaymentNotFound();
        }

        return $payment;
    }
}