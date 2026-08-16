<?php

namespace App\Catalog\Zone\Application\Create;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;

class CreateZoneCommand extends BaseCommand
{
    public function __construct(
        private string $event,
        private string $day,
        private string $name,
        private float $price,
        private int $quantity,
        private int $hierarchy,
        private bool $numberedSeating,
    ) {
    }

    public static function create(string $event, string $day, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $event,
            $day,
            $payload->string('name'),
            $payload->float('price'),
            $payload->int('quantity'),
            $payload->int('hierarchy'),
            $payload->bool('numberedSeating'),
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

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function hierarchy(): int
    {
        return $this->hierarchy;
    }

    public function numberedSeating(): bool
    {
        return $this->numberedSeating;
    }
}