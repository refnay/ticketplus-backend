<?php

namespace App\Catalog\Zone\Domain;

use App\Catalog\Event\Domain\EventDay;

class Zone
{
    private ZoneId $id;
    private ZoneName $name;
    private ZoneCurrency $currency;
    private ZoneHierarchy $hierarchy;
    private ZoneNumberedSeating $numberedSeating;
    private ZonePrice $price;
    private ZoneQuantity $quantity;
    private ZoneTaxRate $taxRate;
    private EventDay $day;

    public function __construct(
        ZoneId $id,
        ZoneName $name,
        ZoneCurrency $currency,
        ZoneHierarchy $hierarchy,
        ZoneNumberedSeating $numberedSeating,
        ZonePrice $price,
        ZoneQuantity $quantity,
        ZoneTaxRate $taxRate,
        EventDay $day,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->currency = $currency;
        $this->hierarchy = $hierarchy;
        $this->numberedSeating = $numberedSeating;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->taxRate = $taxRate;
        $this->day = $day;
    }

    public static function create(
        ZoneName $name,
        ZoneCurrency $currency,
        ZoneHierarchy $hierarchy,
        ZoneNumberedSeating $numberedSeating,
        ZonePrice $price,
        ZoneQuantity $quantity,
        ZoneTaxRate $taxRate,
        EventDay $day,
    ): self {
        return new self(
            ZoneId::generate(),
            $name,
            $currency,
            $hierarchy,
            $numberedSeating,
            $price,
            $quantity,
            $taxRate,
            $day,
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

    public function currency(): ZoneCurrency
    {
        return $this->currency;
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

    public function taxRate(): ZoneTaxRate
    {
        return $this->taxRate;
    }
    
    public function day(): EventDay
    {
        return $this->day;
    }

    public function changeName(ZoneName $name): void
    {
        $this->name = $name;
    }

    public function changeCurrency(ZoneCurrency $currency): void
    {
        $this->currency = $currency;
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

    public function changeTaxRate(ZoneTaxRate $taxRate): void
    {
        $this->taxRate = $taxRate;
    }

    public function total(): float
    {
        return $this->price()->value() + $this->price()->value() * $this->taxRate()->decimal();
    }
}
