<?php

namespace App\Sale\Discount\Application\Find;

use App\Sale\Discount\Domain\Discount;
use JsonSerializable;
use Override;

class DiscountResponse implements JsonSerializable
{
    public function __construct(
        readonly private string $id,
        readonly private bool $active,
        readonly private string $code,
        readonly private string $startDate,
        readonly private string $endDate,
        readonly private int $type,
        readonly private array $usage,
        readonly private float $value,
        readonly private string $eventId,
    ) {
    }

    public static function create(Discount $discount): self
    {
        return new self(
            $discount->id()->value(),
            $discount->active()->value(),
            $discount->code()->value(),
            $discount->startDate()->format(),
            $discount->endDate()->format(),
            $discount->type()->value(),
            $discount->usage()->toArray(),
            $discount->value()->value(),
            $discount->eventId()->value(),
        );
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
