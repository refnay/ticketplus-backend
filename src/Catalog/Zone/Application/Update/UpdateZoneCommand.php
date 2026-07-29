<?php

namespace App\Catalog\Zone\Application\Update;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;

class UpdateZoneCommand extends BaseCommand
{
    public function __construct(
        private string $id,
        private string $event,
        private string $day,
        private string $name,
        private string $currency,
        private float $price,
        private float $taxRate,
        private int $totalQuantity,
        private int $soldQuantity,
        private int $hierarchy,
        private bool $numberedSeating,
    ) {
    }

    public static function create(string $id, string $event, string $day, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $id,
            $event,
            $day,
            $payload->string('name'),
            $payload->string('currency'),
            $payload->float('price'),
            $payload->float('taxRate'),
            $payload->int('totalQuantity'),
            $payload->int('soldQuantity'),
            $payload->int('hierarchy'),
            $payload->bool('numberedSeating'),
        );
    }

    public function id(): string
    {
        return $this->id;
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

    public function totalQuantity(): int
    {
        return $this->totalQuantity;
    }

    public function soldQuantity(): int
    {
        return $this->soldQuantity;
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