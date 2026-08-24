<?php

namespace App\Sale\Discount\Infrastructure\Persistence;

use App\Sale\Discount\Domain\Discount;
use App\Sale\Discount\Domain\DiscountActive;
use App\Sale\Discount\Domain\DiscountCode;
use App\Sale\Discount\Domain\DiscountEndDate;
use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Discount\Domain\DiscountStartDate;
use App\Sale\Discount\Domain\DiscountType;
use App\Sale\Discount\Domain\DiscountUsage;
use App\Sale\Discount\Domain\DiscountValue;
use App\Sale\Reference\Event\Domain\EventId;
use App\Shared\Infrastructure\Persistence\Entity\Discount as DiscountEntity;

class DiscountMapper
{
    public function __construct(private RelationFetcher $fetcher)
    {
    }

    public function newEntity(Discount $discount): DiscountEntity
    {
        $entity = new DiscountEntity();
        
        $entity->setId($discount->id()->toUuid());
        $entity->setCode($discount->code()->value());
        $entity->setType($discount->type()->value());
        $entity->setValue($discount->value()->value());
        $entity->setStartDate($discount->startDate()->value());
        $entity->setEndDate($discount->endDate()->value());
        $entity->setUsageLimit($discount->usage()->limit());
        $entity->setUsageCount($discount->usage()->count());
        $entity->setActive($discount->active()->value());
        $entity->setEvent($this->fetcher->event($discount->eventId()));

        return $entity;
    }

    public function newDomain(DiscountEntity $entity): Discount
    {
        $discount = new Discount(
            DiscountId::fromString($entity->getId()),
            DiscountActive::fromBool($entity->isActive()),
            DiscountCode::fromString($entity->getCode()),
            DiscountStartDate::fromDateTime($entity->getStartDate()),
            DiscountEndDate::fromDateTime($entity->getEndDate()),
            DiscountType::fromInt($entity->getType()),
            DiscountUsage::create($entity->getUsageLimit(), $entity->getUsageCount()),
            DiscountValue::fromFloat($entity->getValue()),
            EventId::fromString($entity->getEvent()->getId()),
        );

        return $discount;
    }

    public function update(DiscountEntity $entity, Discount $discount): void
    {
        $entity->setCode($discount->code()->value());
        $entity->setType($discount->type()->value());
        $entity->setValue($discount->value()->value());
        $entity->setStartDate($discount->startDate()->value());
        $entity->setEndDate($discount->endDate()->value());
        $entity->setUsageLimit($discount->usage()->limit());
        $entity->setUsageCount($discount->usage()->count());
        $entity->setActive($discount->active()->value());
    }

    public function entityClass(): string
    {
        return DiscountEntity::class;
    }
}
