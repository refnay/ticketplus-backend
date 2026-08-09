<?php

namespace App\Sale\Payment\Domain;

use App\Sale\Purchase\Domain\Purchase;

class Payment
{
    private PaymentId $id;
    private PaymentAmount $amount;
    private ?PaymentExternalReference $externalReference = null;
    private PaymentPaymentMethod $paymentMethod;
    private PaymentStatus $status;
    private Purchase $purchase;

    public function __construct(
        PaymentId $id,
        PaymentAmount $amount,
        PaymentExternalReference $externalReference,
        PaymentPaymentMethod $paymentMethod,
        PaymentStatus $status,
        Purchase $purchase,
    ) {
        $this->id = $id;
        $this->amount = $amount;
        $this->externalReference = $externalReference;
        $this->paymentMethod = $paymentMethod;
        $this->status = $status;
        $this->purchase = $purchase;
    }

    public static function create(
        PaymentAmount $amount,
        PaymentExternalReference $externalReference,
        PaymentPaymentMethod $paymentMethod,
        PaymentStatus $status,
        Purchase $purchase,
    ): self {
        return new self(PaymentId::generate(), $amount, $externalReference, $paymentMethod, $status, $purchase);
    }

    public function id(): PaymentId
    {
        return $this->id;
    }

    public function amount(): PaymentAmount
    {
        return $this->amount;
    }

    public function externalReference(): PaymentExternalReference
    {
        return $this->externalReference ?? PaymentExternalReference::fromNull();
    }

    public function paymentMethod(): PaymentPaymentMethod
    {
        return $this->paymentMethod;
    }

    public function status(): PaymentStatus
    {
        return $this->status;
    }

    public function purchase(): Purchase
    {
        return $this->purchase;
    }

    public function changeAmount(PaymentAmount $amount): void
    {
        $this->amount = $amount;
    }

    public function changeExternalReference(PaymentExternalReference $externalReference): void
    {
        $this->externalReference = $externalReference;
    }

    public function changePaymentMethod(PaymentPaymentMethod $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function changeStatus(PaymentStatus $status): void
    {
        $this->status = $status;
    }
}
