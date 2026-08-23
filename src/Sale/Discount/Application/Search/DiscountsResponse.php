<?php

namespace App\Sale\Discount\Application\Search;

use JsonSerializable;
use Override;

class DiscountsResponse implements JsonSerializable
{
    private array $discounts = [];

    public function __construct(private int $total, DiscountResponse ...$discounts)
    {
        $this->discounts = $discounts;
    }

    public function discounts(): array
    {
        return $this->discounts;
    }
    
    public function total(): int
    {
        return $this->total;
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
