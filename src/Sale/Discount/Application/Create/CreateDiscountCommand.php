<?php

namespace App\Sale\Discount\Application\Create;

use App\Shared\Application\Input\PayloadMapper;

class CreateDiscountCommand
{
    public function __construct(
        private string $event,
        private bool $active,
        private string $code,
        private string $startDate,
        private string $endDate,
        private int $type,
        private int $usageLimit,
        private float $value,
    ) {
    }

    public static function create(string $event, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $event,
            $payload->bool('active'),
            $payload->string('code'),
            $payload->string('startDate'),
            $payload->string('endDate'),
            $payload->int('type'),
            $payload->int('usageLimit'),
            $payload->float('value'),
        );
    }

    public function event(): string
    {
        return $this->event;
    }

    public function active(): bool
    {
        return $this->active;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function startDate(): string
    {
        return $this->startDate;
    }

    public function endDate(): string
    {
        return $this->endDate;
    }

    public function type(): int
    {
        return $this->type;
    }

    public function usageLimit(): int
    {
        return $this->usageLimit;
    }

    public function value(): float
    {
        return $this->value;
    }
}
