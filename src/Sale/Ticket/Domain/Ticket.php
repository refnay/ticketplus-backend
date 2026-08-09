<?php

namespace App\Sale\Ticket\Domain;

use App\s\Zone\Domain\TicketInformation;
use App\Sale\Purchase\Domain\Purchase;
use App\Sale\Purchase\Domain\SeatId;
use App\Sale\Purchase\Domain\ZoneId;

class Ticket
{
    private TicketId $id;
    private TicketInformation $information;
    private TicketPrice $price;
    private TicketQRCode $qrCode;
    private TicketStatus $status;
    private ZoneId $zoneId;
    private Purchase $purchase;
    private ?SeatId $seatId = null;

    public function __construct(
        TicketId $id,
        TicketInformation $information,
        TicketPrice $price,
        TicketQRCode $qrCode,
        TicketStatus $status,
        ZoneId $zoneId,
    ) {
        $this->id = $id;
        $this->information = $information;
        $this->price = $price;
        $this->qrCode = $qrCode;
        $this->status = $status;
        $this->zoneId = $zoneId;
    }

    public static function create(
        TicketInformation $information,
        TicketPrice $price,
        TicketQRCode $qrCode,
        TicketStatus $status,
        ZoneId $zoneId,
    ): self {
        return new self(
            TicketId::generate(),
            $information,
            $price,
            $qrCode,
            $status,
            $zoneId,
        );
    }

    public function id(): TicketId
    {
        return $this->id;
    }

    public function information(): TicketInformation
    {
        return $this->information;
    }

    public function price(): TicketPrice
    {
        return $this->price;
    }

    public function qrCode(): TicketQRCode
    {
        return $this->qrCode;
    }

    public function status(): TicketStatus
    {
        return $this->status;
    }

    public function purchase(): Purchase
    {
        return $this->purchase;
    }

    public function zoneId(): ZoneId
    {
        return $this->zoneId;
    }

    public function seatId(): ?SeatId
    {
        return $this->seatId;
    }
    
    public function changePrice(TicketPrice $price): void
    {
        $this->price = $price;
    }

    public function changeQRCode(TicketQRCode $qrCode): void
    {
        $this->qrCode = $qrCode;
    }

    public function changeStatus(TicketStatus $status): void
    {
        $this->status = $status;
    }

    public function changePurchase(Purchase $purchase): void
    {
        $this->purchase = $purchase;
    }

    public function changeSeatId(?SeatId $seatId): void
    {
        $this->seatId = $seatId;
    }
}
