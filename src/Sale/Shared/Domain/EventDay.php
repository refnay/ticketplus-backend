<?php

namespace App\Sale\Shared\Domain;

use DateTimeImmutable;

class EventDay
{
    private string $id;
    private DateTimeImmutable $date;
    private string $currency;
    private float $taxRate;
    private string $eventName;

    public function __construct(
        string $id,
        DateTimeImmutable $date,
        string $currency,
        float $taxRate,
        string $eventName,
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->currency = $currency;
        $this->taxRate = $taxRate;
        $this->eventName = $eventName;
    }

    public static function create(
        string $id,
        string $date,
        string $currency,
        float $taxRate,
        string $eventName,
    ): self {
        return new self($id, DateTimeImmutable::createFromFormat('Y-m-d', $date), $currency, $taxRate, $eventName);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function taxRate(): float
    {
        return $this->taxRate;
    }

    public function eventName(): string
    {
        return $this->eventName;
    }
}
