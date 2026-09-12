<?php

namespace App\Catalog\Seat\Application\Create;

use App\Shared\Application\Input\PayloadMapper;

class CreateSeatCommand
{
    public function __construct(
        private string $zone,
        private string $code,
    ) {
    }

    public static function create(string $zone, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $zone,
            $payload->string('code'),
        );
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
