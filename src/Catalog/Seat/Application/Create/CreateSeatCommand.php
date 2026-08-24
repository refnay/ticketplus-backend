<?php

namespace App\Catalog\Seat\Application\Create;

use App\Shared\Application\Input\PayloadMapper;

class CreateSeatCommand
{
    public function __construct(
        private string $event,
        private string $day,
        private string $zone,
        private string $code,
    ) {
    }

    public static function create(string $event, string $day, string $zone, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $event,
            $day,
            $zone,
            $payload->string('code'),
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

    public function code(): string
    {
        return $this->code;
    }
}