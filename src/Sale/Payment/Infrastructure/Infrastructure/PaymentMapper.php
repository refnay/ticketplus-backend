<?php

namespace App\Sale\Payment\Infrastructure\Persistence;

use App\Sale\Discount\Domain\Discount;
use App\Sale\Discount\Domain\DiscountActive;
use App\Sale\Discount\Domain\DiscountCode;
use App\Sale\Discount\Domain\DiscountEndDate;
use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Discount\Domain\DiscountStartDate;
use App\Sale\Discount\Domain\DiscountType;
use App\Sale\Discount\Domain\DiscountUsage;
use App\Sale\Discount\Domain\DiscountValue;
use App\Sale\Payment\Domain\Payment;
use App\Sale\Payment\Domain\PaymentAmount;
use App\Sale\Payment\Domain\PaymentExternalReference;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Payment\Domain\PaymentMethod;
use App\Sale\Payment\Domain\PaymentStatus;
use App\Sale\Purchase\Domain\EventId;
use App\Sale\Purchase\Domain\Purchase;
use App\Sale\Purchase\Domain\PurchaseCurrency;
use App\Sale\Purchase\Domain\PurchaseId;
use App\Sale\Purchase\Domain\PurchasePaymentMethod;
use App\Sale\Purchase\Domain\PurchaseStatus;
use App\Sale\Purchase\Domain\PurchaseSubTotal;
use App\Sale\Purchase\Domain\PurchaseTax;
use App\Sale\Purchase\Domain\PurchaseTotal;
use App\Sale\Purchase\Domain\UserId;
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
        $entity->setPurchase($this->fetcher->purchase($payment->purchase()->id()));

        return $entity;
    }

    public function newDomain(PaymentEntity $entity): Payment
    {   
        $purchaseEntity = $entity->getPurchase();
        $discountEntity = $purchaseEntity->getDiscount();
        $discount = null;
        
        if (!is_null($discountEntity)) {
            $discount = new Discount(
                DiscountId::fromString($discountEntity->getId()),
                DiscountActive::fromBool($discountEntity->isActive()),
                DiscountCode::fromString($discountEntity->getCode()),
                DiscountStartDate::fromDateTime($discountEntity->getStartDate()),
                DiscountEndDate::fromDateTime($discountEntity->getEndDate()),
                DiscountType::fromInt($discountEntity->getType()),
                DiscountUsage::create($discountEntity->getUsageLimit(), $discountEntity->getUsageCount()),
                DiscountValue::fromFloat($discountEntity->getValue()),
                EventId::fromString($discountEntity->getEvent()->getId()),
            );
        }
        
        $purchase = new Purchase(
            PurchaseId::fromString($purchaseEntity->getId()),
            PurchaseCurrency::fromString($purchaseEntity->getCurrency()),
            PurchasePaymentMethod::fromInt($purchaseEntity->getPaymentMethod()),
            PurchaseStatus::fromInt($purchaseEntity->getStatus()),
            PurchaseSubTotal::fromFloat($purchaseEntity->getSubTotal()),
            PurchaseTax::fromFloat($purchaseEntity->getTax()),
            PurchaseTotal::fromFloat($purchaseEntity->getTotal()),
            UserId::fromString($purchaseEntity->getAttendee()->getId())
        );
        $purchase->changeDiscount($discount);

        $payment = new Payment(
            PaymentId::fromString($entity->getId()),
            PaymentAmount::fromFloat($entity->getAmount()),
            PaymentExternalReference::fromString($entity->getExternalReference()),
            PaymentMethod::fromInt($entity->getPaymentMethod()),
            PaymentStatus::fromInt($entity->getStatus()),
            $purchase,
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