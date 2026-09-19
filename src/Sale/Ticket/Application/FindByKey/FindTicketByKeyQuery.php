<?php

namespace App\Sale\Ticket\Application\FindByKey;

use App\Shared\Application\Input\PayloadMapper;

class FindTicketByKeyQuery
{
    public function __construct(private string $key) {}

    public static function fromQuery(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self($payload->string('key'));
    }

    public function key(): string
    {
        return $this->key;
    }
}