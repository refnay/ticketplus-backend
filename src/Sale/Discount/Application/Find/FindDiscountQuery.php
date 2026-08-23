<?php

namespace App\Sale\Discount\Application\Find;

use App\Shared\Application\Query\BaseQuery;

class FindDiscountQuery extends BaseQuery
{
    public function __construct(private string $id, private string $event)
    {
    }

    public static function create(string $id, string $event): self
    {
        return new self($id, $event);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function event(): string
    {
        return $this->event;
    }
}
