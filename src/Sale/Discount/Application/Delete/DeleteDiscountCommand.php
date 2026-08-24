<?php

namespace App\Sale\Discount\Application\Delete;


class DeleteDiscountCommand
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
