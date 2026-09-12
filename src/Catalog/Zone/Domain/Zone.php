<?php

namespace App\Catalog\Zone\Domain;

use App\Catalog\Event\Domain\EventDayId;

class Zone
{
    private ZoneId $id;
    private ZoneName $name;
    private ZoneHierarchy $hierarchy;
    private ZoneNumberedSeating $numberedSeating;
    private ZonePrice $price;
    private ZoneQuantity $quantity;
    private ?ZoneCanvas $canvas = null;
    private EventDayId $dayId;

    public function __construct(
        ZoneId $id,
        ZoneName $name,
        ZoneHierarchy $hierarchy,
        ZoneNumberedSeating $numberedSeating,
        ZonePrice $price,
        ZoneQuantity $quantity,
        ZoneCanvas $canvas,
        EventDayId $dayId,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->hierarchy = $hierarchy;
        $this->numberedSeating = $numberedSeating;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->canvas = $canvas;
        $this->dayId = $dayId;
    }

    public static function create(
        ZoneName $name,
        ZoneHierarchy $hierarchy,
        ZoneNumberedSeating $numberedSeating,
        ZonePrice $price,
        ZoneQuantity $quantity,
        EventDayId $dayId,
    ): self {
        return new self(
            ZoneId::generate(),
            $name,
            $hierarchy,
            $numberedSeating,
            $price,
            $quantity,
            ZoneCanvas::fromNull(),
            $dayId,
        );
    }

    public function id(): ZoneId
    {
        return $this->id;
    }

    public function name(): ZoneName
    {
        return $this->name;
    }

    public function hierarchy(): ZoneHierarchy
    {
        return $this->hierarchy;
    }

    public function numberedSeating(): ZoneNumberedSeating
    {
        return $this->numberedSeating;
    }

    public function price(): ZonePrice
    {
        return $this->price;
    }

    public function quantity(): ZoneQuantity
    {
        return $this->quantity;
    }

    public function dayId(): EventDayId
    {
        return $this->dayId;
    }

    public function canvas(): ZoneCanvas
    {
        return $this->canvas ?? ZoneCanvas::fromNull();
    }

    public function changeName(ZoneName $name): void
    {
        $this->name = $name;
    }

    public function changeHierarchy(ZoneHierarchy $hierarchy): void
    {
        $this->hierarchy = $hierarchy;
    }

    public function changeNumberedSeating(ZoneNumberedSeating $numberedSeating): void
    {
        $this->numberedSeating = $numberedSeating;
    }

    public function changePrice(ZonePrice $price): void
    {
        $this->price = $price;
    }

    public function changeQuantity(ZoneQuantity $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function changeTotalQuantity(int $total): void
    {
        $this->quantity = $this->quantity->changeTotal($total);
    }

    public function changeSoldQuantity(int $sold): void
    {
        $this->quantity = $this->quantity->changeSold($sold);
    }

    public function changeReservedQuantity(int $reserved): void
    {
        $this->quantity = $this->quantity->changeReserved($reserved);
    }

    public function changeCanvas(ZoneCanvas $canvas): void
    {
        $this->canvas = $canvas;
    }
}
