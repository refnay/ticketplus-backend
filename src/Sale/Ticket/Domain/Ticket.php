<?php

namespace App\Sale\Ticket\Domain;

use App\Sale\Purchase\Domain\PurchaseId;
use App\Sale\Shared\Domain\SeatId;
use App\Sale\Shared\Domain\ZoneId;

class Ticket
{
    private TicketId $id;
    private TicketInformation $information;
    private TicketPrice $price;
    private TicketQRCode $qrCode;
    private TicketStatus $status;
    private ZoneId $zoneId;
    private PurchaseId $purchaseId;
    private ?SeatId $seatId = null;

    public function __construct(
        TicketId $id,
        TicketInformation $information,
        TicketPrice $price,
        TicketQRCode $qrCode,
        TicketStatus $status,
        PurchaseId $purchaseId,
        ZoneId $zoneId,
    ) {
        $this->id = $id;
        $this->information = $information;
        $this->price = $price;
        $this->qrCode = $qrCode;
        $this->status = $status;
        $this->purchaseId = $purchaseId;
        $this->zoneId = $zoneId;
    }

    public static function create(
        TicketInformation $information,
        TicketPrice $price,
        TicketQRCode $qrCode,
        TicketStatus $status,
        PurchaseId $purchaseId,
        ZoneId $zoneId,
    ): self {
        return new self(
            TicketId::generate(),
            $information,
            $price,
            $qrCode,
            $status,
            $purchaseId,
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

    public function purchaseId(): PurchaseId
    {
        return $this->purchaseId;
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

    public function changeSeatId(?SeatId $seatId): void
    {
        $this->seatId = $seatId;
    }
}
