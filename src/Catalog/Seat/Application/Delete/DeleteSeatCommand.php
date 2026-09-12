<?php

namespace App\Catalog\Seat\Application\Delete;


class DeleteSeatCommand
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
