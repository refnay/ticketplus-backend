<?php

namespace App\Sale\Payment\Domain\Services;

use App\Sale\Payment\Domain\Exceptions\PaymentNotFound;
use App\Sale\Payment\Domain\Payment;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Payment\Domain\PaymentRepository;

class PaymentFinder
{
    public function __construct(private PaymentRepository $repository)
    {
    }

    public function __invoke(PaymentId $id): Payment
    {
        $payment = $this->repository->find($id);

        if (is_null($payment)) {
            throw new PaymentNotFound();
        }

        return $payment;
    }
}
