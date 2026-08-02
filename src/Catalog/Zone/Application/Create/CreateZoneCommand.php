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
        private string $currency,
        private float $price,
        private float $taxRate,
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
            $payload->string('currency'),
            $payload->float('price'),
            $payload->float('taxRate'),
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

    public function currency(): string
    {
        return $this->currency;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function taxRate(): float
    {
        return $this->taxRate;
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