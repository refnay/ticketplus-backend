<?php

namespace App\Catalog\Seat\Domain;

use App\Catalog\Zone\Domain\Zone;

class Seat
{
    private SeatId $id;
    private SeatCode $code;
    private SeatStatus $status;
    private Zone $zone;

    public function __construct(SeatId $id, SeatCode $code, SeatStatus $status, Zone $zone)
    {
        $this->id = $id;
        $this->code = $code;
        $this->status = $status;
        $this->zone = $zone;
    }

    public static function create(SeatCode $code, SeatStatus $status, Zone $zone): self
    {
        return new self(SeatId::generate(), $code, $status, $zone);
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
    
    public function zone(): Zone
    {
        return $this->zone;
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
