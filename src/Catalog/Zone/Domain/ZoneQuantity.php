<?php

namespace App\Catalog\Zone\Domain;

class ZoneQuantity
{
    public function __construct(private int $total, private int $sold)
    {
    }

    public static function create(int $total, int $sold): self
    {
        return new self($total, $sold);
    }

    public static function fromData(array $data): self
    {
        return new self($data['total'], $data['sold']);
    }

    public function total(): int
    {
        return $this->total;
    }

    public function sold(): int
    {
        return $this->sold;
    }

    public function toArray(): array
    {
        return [
            'total' => $this->total,
            'sold' => $this->sold,
        ];
    }
}
