<?php

namespace App\Sale\Ticket\Infrastructure\Persistence;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Shared\Domain\ZoneId;
use App\Sale\Ticket\Domain\Ticket;
use App\Sale\Ticket\Domain\TicketCode;
use App\Sale\Ticket\Domain\TicketId;
use App\Sale\Ticket\Domain\TicketPrice;
use App\Sale\Ticket\Domain\TicketQRCode;
use App\Sale\Ticket\Domain\TicketInformation;
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
        $entity->setCode($ticket->code()->value());
        $entity->setSeatCode($ticket->information()->seatCode());
        $entity->setPrice($ticket->price()->value());
        $entity->setStatus($ticket->status()->value());
        $entity->setPurchase($this->fetcher->order($ticket->orderId()));
        $entity->setZone($this->fetcher->zone($ticket->zoneId()));

        if (!is_null($ticket->seatId())) {
            $entity->setSeat($this->fetcher->seat($ticket->seatId()));
        }

        return $entity;
    }

    public function newDomain(TicketEntity $entity): Ticket
    { 
        $ticket = new Ticket(
            TicketId::fromString($entity->getId()),
            TicketInformation::create(
                $entity->getDate(),
                $entity->getEventName(),
                $entity->getZoneName(),
                $entity->getSeatCode()
            ),
            TicketCode::fromString($entity->getCode()),
            TicketPrice::fromFloat($entity->getPrice()),
            TicketQRCode::fromString($entity->getQRCode()),
            TicketStatus::fromInt($entity->getStatus()),
            OrderId::fromString($entity->getPurchase()->getId()),
            ZoneId::fromString($entity->getZone()->getId()),
        );
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