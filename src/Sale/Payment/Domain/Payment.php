<?php

namespace App\Sale\Payment\Domain;

use App\Sale\Order\Domain\OrderId;

class Payment
{
    private PaymentId $id;
    private PaymentAmount $amount;
    private ?PaymentExternalReference $externalReference = null;
    private ?PaymentToken $token = null;
    private PaymentMethod $method;
    private PaymentStatus $status;
    private OrderId $orderId;

    public function __construct(
        PaymentId $id,
        PaymentAmount $amount,
        PaymentExternalReference $externalReference,
        PaymentToken $token,
        PaymentMethod $method,
        PaymentStatus $status,
        OrderId $orderId,
    ) {
        $this->id = $id;
        $this->amount = $amount;
        $this->externalReference = $externalReference;
        $this->token = $token;
        $this->method = $method;
        $this->status = $status;
        $this->orderId = $orderId;
    }

    public static function create(PaymentAmount $amount, PaymentMethod $method, OrderId $orderId): self
    {
        return new self(
            PaymentId::generate(),
            $amount,
            PaymentExternalReference::fromNull(),
            PaymentToken::fromNull(),
            $method,
            PaymentStatus::pending(),
            $orderId
        );
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

    public function token(): PaymentToken
    {
        return $this->token ?? PaymentToken::fromNull();
    }

    public function method(): PaymentMethod
    {
        return $this->method;
    }

    public function status(): PaymentStatus
    {
        return $this->status;
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
    }

    public function changeAmount(PaymentAmount $amount): void
    {
        $this->amount = $amount;
    }

    public function changeExternalReference(PaymentExternalReference $externalReference): void
    {
        $this->externalReference = $externalReference;
    }

    public function changeToken(PaymentToken $token): void
    {
        $this->token = $token;
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
