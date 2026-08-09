<?php

namespace App\Sale\Purchase\Infrastructure\Persistence;

use App\Sale\Purchase\Domain\Purchase;
use App\Sale\Purchase\Domain\PurchaseCurrency;
use App\Sale\Purchase\Domain\PurchaseId;
use App\Sale\Purchase\Domain\PurchasePaymentMethod;
use App\Sale\Purchase\Domain\PurchaseStatus;
use App\Sale\Purchase\Domain\PurchaseSubTotal;
use App\Sale\Purchase\Domain\PurchaseTax;
use App\Sale\Purchase\Domain\PurchaseTotal;
use App\Sale\Shared\Domain\UserId;
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

        if (!is_null($purchase->discountId())) {
            $entity->setDiscount($this->fetcher->discount($purchase->discountId()));
        }

        return $entity;
    }

    public function newDomain(PurchaseEntity $entity): Purchase
    {
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
        $purchase->changeDiscountId($entity->getDiscount()?->getId());

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
