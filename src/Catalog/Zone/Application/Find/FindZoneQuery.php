<?php

namespace App\Catalog\Zone\Application\Find;


class FindZoneQuery
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
