<?php

namespace App\Sale\Order\Application\Create;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;

class CreateOrderCommand extends BaseCommand
{
    public function __construct(
        private string $event,
        private string $day,
        private string $zone,
        private ?string $discount,
        private int $quantity,
        private ?array $seats,
    ) {
    }

    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $payload->string('event'),
            $payload->string('day'),
            $payload->string('zone'),
            $payload->nullableString('discount'),
            $payload->int('quantity'),
            $payload->nullableArray('seats'),
        );
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

    public function discount(): ?string
    {
        return $this->discount;
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