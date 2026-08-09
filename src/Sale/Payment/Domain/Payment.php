<?php

namespace App\Sale\Payment\Domain;

use App\Sale\Purchase\Domain\PurchaseId;

class Payment
{
    private PaymentId $id;
    private PaymentAmount $amount;
    private ?PaymentExternalReference $externalReference = null;
    private PaymentMethod $method;
    private PaymentStatus $status;
    private PurchaseId $purchaseId;

    public function __construct(
        PaymentId $id,
        PaymentAmount $amount,
        PaymentExternalReference $externalReference,
        PaymentMethod $method,
        PaymentStatus $status,
        PurchaseId $purchaseId,
    ) {
        $this->id = $id;
        $this->amount = $amount;
        $this->externalReference = $externalReference;
        $this->method = $method;
        $this->status = $status;
        $this->purchaseId = $purchaseId;
    }

    public static function create(
        PaymentAmount $amount,
        PaymentExternalReference $externalReference,
        PaymentMethod $method,
        PaymentStatus $status,
        PurchaseId $purchaseId,
    ): self {
        return new self(PaymentId::generate(), $amount, $externalReference, $method, $status, $purchaseId);
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

    public function method(): PaymentMethod
    {
        return $this->method;
    }

    public function status(): PaymentStatus
    {
        return $this->status;
    }

    public function purchaseId(): PurchaseId
    {
        return $this->purchaseId;
    }

    public function changeAmount(PaymentAmount $amount): void
    {
        $this->amount = $amount;
    }

    public function changeExternalReference(PaymentExternalReference $externalReference): void
    {
        $this->externalReference = $externalReference;
    }

    public function changeMethod(PaymentMethod $method): void
    {
        $this->method = $method;
    }

    public function changeStatus(PaymentStatus $status): void
    {
        $this->status = $status;
    }
}
