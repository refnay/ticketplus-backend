<?php

namespace App\Catalog\Seat\Application\Update;

use App\Shared\Application\Input\PayloadMapper;

class UpdateSeatCommand
{
    public function __construct(
        private string $id,
        private string $code,
        private int $status,
    ) {
    }

    public static function create(string $id, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $id,
            $payload->string('code'),
            $payload->int('status'),
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function status(): int
    {
        return $this->status;
    }
}
