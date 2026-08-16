<?php

namespace App\Catalog\Zone\Domain;

class ZoneQuantity
{
    public function __construct(private int $total, private int $sold, private int $reserved)
    {
    }

    public static function create(int $total, int $sold, int $reserved): self
    {
        return new self($total, $sold, $reserved);
    }

    public static function fromTotal(int $total): self
    {
        return new self($total, 0, 0);
    }

    public static function fromData(array $data): self
    {
        return new self($data['total'], $data['sold'], $data['reserved']);
    }

    public function total(): int
    {
        return $this->total;
    }

    public function sold(): int
    {
        return $this->sold;
    }

    public function reserved(): int
    {
        return $this->reserved;
    }

    public function toArray(): array
    {
        return [
            'total' => $this->total,
            'sold' => $this->sold,
            'reserved' => $this->reserved,
        ];
    }

    public static function changeReserved(int $reserved): self
    {
        return new self($this->total, $this->sold, $reserved);
    }
}
