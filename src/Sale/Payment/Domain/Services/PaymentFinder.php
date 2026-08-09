<?php

namespace App\Sale\Payment\Domain\Services;

use App\Sale\Payment\Domain\Exceptions\PaymentNotFound;
use App\Sale\Payment\Domain\Payment;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Payment\Domain\PaymentRepository;
use App\Sale\Order\Domain\OrderId;

class PaymentFinder
{
    public function __construct(private PaymentRepository $repository)
    {
    }

    public function __invoke(PaymentId $id, OrderId $orderId): Payment
    {
        $payment = $this->repository->findById($id, $orderId);

        if (is_null($payment)) {
            throw new PaymentNotFound();
        }

        return $payment;
    }
}