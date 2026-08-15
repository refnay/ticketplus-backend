<?php

namespace App\Sale\Discount\Domain;

class DiscountUsage
{
    public function __construct(private int $limit, private int $count)
    {
    }

    public static function create(int $limit, int $count): self
    {
        return new self($limit, $count);
    }

    public static function fromLimit(int $limit): self
    {
        return new self($limit, 0);
    }

    public function isAvailable(): bool
    {
        return $this->count < $this->limit;
    }

    public static function fromData(array $data): self
    {
        return new self($data['limit'], $data['count']);
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public function count(): int
    {
        return $this->count;
    }

    public function toArray(): array
    {
        return [
            'limit' => $this->limit,
            'count' => $this->count,
        ];
    }
}
