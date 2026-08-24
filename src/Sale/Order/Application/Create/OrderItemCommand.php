<?php

namespace App\Sale\Order\Application\Create;

use App\Shared\Application\Input\PayloadMapper;

class OrderItemCommand
{
    public function __construct(private string $zone, private int $quantity, private ?array $seats)
    {
    }

    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $payload->string('zone'),
            $payload->int('quantity'),
            $payload->array('seats'),
        );
    }

    public function zone(): string
    {
        return $this->zone;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function seats(): ?array
    {
        return $this->seats;
    }
}
