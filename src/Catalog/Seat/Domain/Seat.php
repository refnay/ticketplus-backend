<?php

namespace App\Catalog\Seat\Domain;

use App\Catalog\Zone\Domain\ZoneId;

class Seat
{
    private SeatId $id;
    private SeatCode $code;
    private SeatStatus $status;
    private ZoneId $zoneId;

    public function __construct(SeatId $id, SeatCode $code, SeatStatus $status, ZoneId $zoneId)
    {
        $this->id = $id;
        $this->code = $code;
        $this->status = $status;
        $this->zoneId = $zoneId;
    }

    public static function create(SeatCode $code, ZoneId $zoneId): self
    {
        return new self(SeatId::generate(), $code, SeatStatus::available(), $zoneId);
    }

    public function id(): SeatId
    {
        return $this->id;
    }

    public function code(): SeatCode
    {
        return $this->code;
    }

    public function status(): SeatStatus
    {
        return $this->status;
    }

    public function zoneId(): ZoneId
    {
        return $this->zoneId;
    }

    public function changeCode(SeatCode $code): void
    {
        $this->code = $code;
    }

    public function changeStatus(SeatStatus $status): void
    {
        $this->status = $status;
    }

    public function toChooser(): array
    {
        return [
            'code' => $this->id()->value(),
            'label' => $this->code()->value(),
            'status' => $this->status()->value(),
        ];
    }
}
