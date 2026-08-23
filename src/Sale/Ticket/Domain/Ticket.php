<?php

namespace App\Sale\Ticket\Domain;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Shared\Domain\SeatId;
use App\Sale\Shared\Domain\ZoneId;

class Ticket
{
    private TicketId $id;
    private TicketInformation $information;
    private TicketCode $code;
    private TicketPrice $price;
    private TicketQRCode $qrCode;
    private TicketStatus $status;
    private ZoneId $zoneId;
    private OrderId $orderId;
    private ?SeatId $seatId = null;

    public function __construct(
        TicketId $id,
        TicketInformation $information,
        TicketCode $code,
        TicketPrice $price,
        TicketQRCode $qrCode,
        TicketStatus $status,
        OrderId $orderId,
        ZoneId $zoneId,
    ) {
        $this->id = $id;
        $this->information = $information;
        $this->code = $code;
        $this->price = $price;
        $this->qrCode = $qrCode;
        $this->status = $status;
        $this->orderId = $orderId;
        $this->zoneId = $zoneId;
    }

    public static function create(
        TicketInformation $information,
        TicketPrice $price,
        OrderId $orderId,
        ZoneId $zoneId,
    ): self {
        return new self(
            TicketId::generate(),
            $information,
            TicketCode::generate(),
            $price,
            TicketQRCode::generate(),
            TicketStatus::active(),
            $orderId,
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

    public function code(): TicketCode
    {
        return $this->code;
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

    public function orderId(): OrderId
    {
        return $this->orderId;
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

    public function changeCode(TicketCode $code): void
    {
        $this->code = $code;
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

    public function toArray(): array
    {
        return [
            'information' => $this->information()->toArray(),
            'code' => $this->code()->value(),
            'price' => $this->price()->value(),
            'qrCode' => $this->qrCode()->value(),
        ];
    }

    public function filename(): string
    {
        return sprintf('%s %s', $this->code()->value(), $this->information()->eventName());
    }
}
