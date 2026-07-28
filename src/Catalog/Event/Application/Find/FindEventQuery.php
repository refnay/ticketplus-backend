<?php

namespace App\Catalog\Event\Application\Find;

use App\Shared\Application\Query\BaseQuery;

class FindEventQuery extends BaseQuery
{
    public function __construct(private string $id)
    {
    }

    public static function create(string $id): self
    {
        return new self($id);
    }

    public function id(): string
    {
        return $this->id;
    }
}
