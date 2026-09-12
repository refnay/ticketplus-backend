<?php

namespace App\Sale\Reference\Zone\Domain;

class Zone
{
    private string $id;
    private string $name;
    private float $price;
    private int $quantity;
    private bool $numberedSeating;
    private string $dayId;

    public function __construct(
        string $id,
        string $name,
        float $price,
        int $quantity,
        bool $numberedSeating,
        string $dayId,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->numberedSeating = $numberedSeating;
        $this->dayId = $dayId;
    }

    public static function create(
        string $id,
        string $name,
        float $price,
        int $quantity,
        bool $numberedSeating,
        string $dayId,
    ): self {
        return new self($id, $name, $price, $quantity, $numberedSeating, $dayId);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function numberedSeating(): bool
    {
        return $this->numberedSeating;
    }

    public function dayId(): string
    {
        return $this->dayId;
    }
}
