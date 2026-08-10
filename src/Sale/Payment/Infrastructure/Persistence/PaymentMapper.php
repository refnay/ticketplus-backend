<?php

namespace App\Sale\Payment\Infrastructure\Persistence;

use App\Sale\Payment\Domain\Payment;
use App\Sale\Payment\Domain\PaymentAmount;
use App\Sale\Payment\Domain\PaymentExternalReference;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Payment\Domain\PaymentMethod;
use App\Sale\Payment\Domain\PaymentStatus;
use App\Sale\Order\Domain\OrderId;
use App\Shared\Infrastructure\Persistence\Entity\Payment as PaymentEntity;

class PaymentMapper
{
    public function __construct(private RelationFetcher $fetcher)
    {
    }

    public function newEntity(Payment $payment): PaymentEntity
    {
        $entity = new PaymentEntity();
        
        $entity->setId($payment->id()->toUuid());
        $entity->setAmount($payment->amount()->value());
        $entity->setPaymentMethod($payment->method()->value());
        $entity->setStatus($payment->status()->value());
        $entity->setExternalReference($payment->externalReference()->value());
        $entity->setPurchase($this->fetcher->order($payment->orderId()));

        return $entity;
    }

    public function newDomain(PaymentEntity $entity): Payment
    {   
        $payment = new Payment(
            PaymentId::fromString($entity->getId()),
            PaymentAmount::fromFloat($entity->getAmount()),
            PaymentExternalReference::fromString($entity->getExternalReference()),
            PaymentMethod::fromInt($entity->getPaymentMethod()),
            PaymentStatus::fromInt($entity->getStatus()),
            OrderId::fromString($entity->getPurchase()->getId()),
        );

        return $payment;
    }

    public function update(PaymentEntity $entity, Payment $payment): void
    {
        $entity->setAmount($payment->amount()->value());
        $entity->setPaymentMethod($payment->method()->value());
        $entity->setStatus($payment->status()->value());
        $entity->setExternalReference($payment->externalReference()->value());
    }

    public function entityClass(): string
    {
        return PaymentEntity::class;
    }
}