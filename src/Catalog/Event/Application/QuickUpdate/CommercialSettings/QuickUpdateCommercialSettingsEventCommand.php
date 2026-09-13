<?php

namespace App\Catalog\Event\Application\QuickUpdate\CommercialSettings;

use App\Shared\Application\Input\PayloadMapper;

class QuickUpdateCommercialSettingsEventCommand
{
    public function __construct(
        private string $id,
        private string $currency,
        private float $taxRate,
        private int $orderLimit,
    ) {}

    public static function create(string $id, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $id,
            $payload->string('currency'),
            $payload->float('taxRate'),
            $payload->int('orderLimit'),
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function taxRate(): float
    {
        return $this->taxRate;
    }

    public function orderLimit(): int
    {
        return $this->orderLimit;
    }
}
