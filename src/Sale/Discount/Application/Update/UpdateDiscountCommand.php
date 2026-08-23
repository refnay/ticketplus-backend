<?php

namespace App\Sale\Discount\Application\Update;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;

class UpdateDiscountCommand extends BaseCommand
{
    public function __construct(
        private string $id,
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

    public static function create(string $id, string $event, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $id,
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

    public function id(): string
    {
        return $this->id;
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
