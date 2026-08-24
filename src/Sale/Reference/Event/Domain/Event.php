<?php

namespace App\Sale\Reference\Event\Domain;

class Event
{
    public function __construct(
        private string $id,
        private string $currency,
        private string $name,
        private float $taxRate,
    ) {
    }

    public static function create(string $id, string $currency, string $name, float $taxRate): self
    {
        return new self($id, $currency, $name, $taxRate);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function taxRate(): float
    {
        return $this->taxRate;
    }
}
