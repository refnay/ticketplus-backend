<?php

namespace App\Sale\Shared\Domain;

use DateTimeImmutable;

class EventDay
{
    private string $id;
    private string $currency;
    private string $event;
    private float $taxRate;
    private DateTimeImmutable $date;

    public function __construct(
        string $id,
        string $currency,
        string $event,
        float $taxRate,
        DateTimeImmutable $date,
    ) {
        $this->id = $id;
        $this->currency = $currency;
        $this->event = $event;
        $this->taxRate = $taxRate;
        $this->date = $date;
    }

    public static function create(
        string $id,
        string $currency,
        string $event,
        string $date,
        float $taxRate,
    ): self {
        return new self($id, $currency, $event,  $taxRate, DateTimeImmutable::createFromFormat('Y-m-d', $date));
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

    public function event(): string
    {
        return $this->event;
    }
}
