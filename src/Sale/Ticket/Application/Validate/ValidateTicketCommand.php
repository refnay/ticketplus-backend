<?php

namespace App\Sale\Ticket\Application\Validate;

class ValidateTicketCommand
{
    public function __construct(private string $key) {}

    public static function create(string $key): self
    {
        return new self($key);
    }

    public function key(): string
    {
        return $this->key;
    }
}