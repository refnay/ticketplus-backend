<?php

namespace App\Sale\Order\Application\Create;

use App\Shared\Domain\Utils\PayloadMapper;

class ZoneCommand
{
    public function __construct(
        private string $id,
        private int $quantity,
        private ?array $seatIds,
    ) {
    }

    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $payload->string('id'),
            $payload->int('quantity'),
            $payload->array('seatIds'),
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function quantity(): string
    {
        return $this->quantity;
    }

    public function seatIds(): ?array
    {
        return $this->seatIds;
    }
}
