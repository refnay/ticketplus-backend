<?php

namespace App\Sale\Ticket\Infrastructure\Persistence;

use App\s\Zone\Domain\TicketInformation;
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
use App\Sale\Purchase\Domain\ZoneId;
use App\Sale\Ticket\Domain\Ticket;
use App\Sale\Ticket\Domain\TicketId;
use App\Sale\Ticket\Domain\TicketPrice;
use App\Sale\Ticket\Domain\TicketQRCode;
use App\Sale\Ticket\Domain\TicketStatus;
use App\Shared\Infrastructure\Persistence\Entity\Ticket as TicketEntity;

class TicketMapper
{
    public function __construct(private RelationFetcher $fetcher)
    {
    }

    public function newEntity(Ticket $ticket): TicketEntity
    {
        $entity = new TicketEntity();
        
        $entity->setId($ticket->id()->toUuid());
        $entity->setQRCode($ticket->qrCode()->toUuid());
        $entity->setEventName($ticket->information()->eventName());
        $entity->setDate($ticket->information()->date());
        $entity->setZoneName($ticket->information()->zoneName());
        $entity->setSeatCode($ticket->information()->seatCode());
        $entity->setPrice($ticket->price()->value());
        $entity->setStatus($ticket->status()->value());
        $entity->setPurchase($this->fetcher->purchase($ticket->purchase()->id()));
        $entity->setZone($this->fetcher->zone($ticket->zoneId()));

        if (!is_null($ticket->seatId())) {
            $entity->setSeat($this->fetcher->seat($ticket->seatId()));
        }

        return $entity;
    }

    public function newDomain(TicketEntity $entity): Ticket
    {   
        $purchaseEntity = $entity->getPurchase();
        $discountEntity = $purchaseEntity->getDiscount();

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

        $ticket = new Ticket(
            TicketId::fromString($entity->getId()),
            TicketInformation::create(
                $entity->getDate(),
                $entity->getEventName(),
                $entity->getZoneName(),
                $entity->getSeatCode()
            ),
            TicketPrice::fromFloat($entity->getPrice()),
            TicketQRCode::fromString($entity->getQRCode()),
            TicketStatus::fromInt($entity->getStatus()),
            ZoneId::fromString($entity->getZone()->getId()),
        );
        $ticket->changePurchase($purchase);
        $ticket->changeSeatId($entity->getSeat()?->getId());

        return $ticket;
    }

    public function update(TicketEntity $entity, Ticket $ticket): void
    {
        $entity->setStatus($ticket->status()->value());
    }

    public function entityClass(): string
    {
        return TicketEntity::class;
    }
}