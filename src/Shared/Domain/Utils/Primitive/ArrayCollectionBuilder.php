<?php

namespace App\Shared\Domain\Utils\Primitive;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

class ArrayCollectionBuilder
{
    public function __construct(private array $items)
    {
    }

    public static function generate(): self
    {
        return new self([]);
    }

    public function add(string $key, mixed $item): void
    {
        $this->items[] = new Parameter($key, $item);
    }

    public function items(): ArrayCollection
    {
        return new ArrayCollection($this->items);
    }
}
