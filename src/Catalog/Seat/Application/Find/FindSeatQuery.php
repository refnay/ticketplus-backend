<?php

namespace App\Catalog\Seat\Application\Find;

use App\Shared\Application\Query\BaseQuery;

class FindSeatQuery extends BaseQuery
{
    public function __construct(private string $id, private string $event, private string $day, private string $zone)
    {
    }

    public static function create(string $id, string $event, string $day, string $zone): self
    {
        return new self($id, $event, $day, $zone);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function event(): string
    {
        return $this->event;
    }

    public function day(): string
    {
        return $this->day;
    }

    public function zone(): string
    {
        return $this->zone;
    }
}
