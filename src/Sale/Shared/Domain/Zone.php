<?php

namespace App\Sale\Shared\Domain;

class Zone
{
    private string $id;
    private string $currency;
    private float $price;
    private float $taxRate;
    private int $quantity;
    private bool $numberedSeating;

    public function __construct(
        string $id,
        string $currency,
        float $price,
        float $taxRate,
        int $quantity,
        bool $numberedSeating,
    ) {
        $this->id = $id;
        $this->currency = $currency;
        $this->price = $price;
        $this->taxRate = $taxRate;
        $this->quantity = $quantity;
        $this->numberedSeating = $numberedSeating;
    }

    public static function create(
        string $id,
        string $currency,
        float $price,
        float $taxRate,
        int $quantity,
        bool $numberedSeating,
    ): self {
        return new self($id, $currency, $price, $taxRate, $quantity, $numberedSeating);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function taxRate(): float
    {
        return $this->taxRate;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function numberedSeating(): bool
    {
        return $this->numberedSeating;
    }
}
