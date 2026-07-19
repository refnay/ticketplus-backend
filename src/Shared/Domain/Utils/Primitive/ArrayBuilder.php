<?php

namespace App\Shared\Domain\Utils\Primitive;

class ArrayBuilder
{
    public function __construct(private array $items) {}

    public static function generate(): self
    {
        return new self([]);
    }

    public function add(mixed $item): void
    {
        $this->items[] = $item;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function removeDuplicates(): void
    {
        $this->items = array_values(
            array_map(
                'unserialize',
                array_unique(array_map('serialize', $this->items))
            )
        );
    }
}
