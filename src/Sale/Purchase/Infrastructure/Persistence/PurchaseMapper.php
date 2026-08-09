<?php

namespace App\Sale\Purchase\Infrastructure\Persistence;

use App\Sale\Discount\Domain\Discount;
use App\Sale\Discount\Domain\DiscountActive;
use App\Sale\Discount\Domain\DiscountCode;
use App\Sale\Discount\Domain\DiscountEndDate;
use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Discount\Domain\DiscountStartDate;
use App\Sale\Discount\Domain\DiscountType;
use App\Sale\Discount\Domain\DiscountUsage;
use App\Sale\Discount\Domain\DiscountValue;
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
use App\Shared\Infrastructure\Persistence\Entity\Purchase as PurchaseEntity;

class PurchaseMapper
{
    public function __construct(private RelationFetcher $fetcher) {}

    public function newEntity(Purchase $purchase): PurchaseEntity
    {
        $entity = new PurchaseEntity();

        $entity->setId($purchase->id()->toUuid());
        $entity->setSubTotal($purchase->subTotal()->value());
        $entity->setTax($purchase->tax()->value());
        $entity->setTotal($purchase->total()->value());
        $entity->setCurrency($purchase->currency()->value());
        $entity->setStatus($purchase->status()->value());
        $entity->setPaymentMethod($purchase->paymentMethod()->value());
        $entity->setAttendee($this->fetcher->user($purchase->userId()));

        $discount = $purchase->discount();

        if (!is_null($discount)) {
            $entity->setDiscount($this->fetcher->discount($discount->id()));
        }

        return $entity;
    }

    public function newDomain(PurchaseEntity $entity): Purchase
    {
        $discountEntity = $entity->getDiscount();
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
            PurchaseId::fromString($entity->getId()),
            PurchaseCurrency::fromString($entity->getCurrency()),
            PurchasePaymentMethod::fromInt($entity->getPaymentMethod()),
            PurchaseStatus::fromInt($entity->getStatus()),
            PurchaseSubTotal::fromFloat($entity->getSubTotal()),
            PurchaseTax::fromFloat($entity->getTax()),
            PurchaseTotal::fromFloat($entity->getTotal()),
            UserId::fromString($entity->getAttendee()->getId())
        );
        $purchase->changeDiscount($discount);

        return $purchase;
    }

    public function update(PurchaseEntity $entity, Purchase $purchase): void
    {
        $entity->setSubTotal($purchase->subTotal()->value());
        $entity->setTax($purchase->tax()->value());
        $entity->setTotal($purchase->total()->value());
        $entity->setCurrency($purchase->currency()->value());
        $entity->setStatus($purchase->status()->value());
        $entity->setPaymentMethod($purchase->paymentMethod()->value());
    }

    public function entityClass(): string
    {
        return PurchaseEntity::class;
    }
}
