<?php

namespace App\Catalog\Event\Application\Create;

use App\Shared\Application\Input\PayloadMapper;

class EventDayCommand
{
    public function __construct(
        private string $date,
        private string $startTime,
        private string $endTime,
        private string $saleStartAt,
        private ?string $description,
    ) {}

    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $payload->string('date'),
            $payload->string('startTime'),
            $payload->string('endTime'),
            $payload->string('saleStartAt'),
            $payload->nullableString('description'),
        );
    }

    public function date(): string
    {
        return $this->date;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function startTime(): string
    {
        return $this->startTime;
    }

    public function saleStartAt(): string
    {
        return $this->saleStartAt;
    }

    public function endTime(): string
    {
        return $this->endTime;
    }
}
