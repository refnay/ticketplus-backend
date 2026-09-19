<?php

namespace App\Sale\Ticket\Application\Validate;

use App\Shared\Application\Input\PayloadMapper;

class ValidateTicketCommand
{
    public function __construct(private string $key) {}

    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self($payload->string('key'));
    }

    public function key(): string
    {
        return $this->key;
    }
}