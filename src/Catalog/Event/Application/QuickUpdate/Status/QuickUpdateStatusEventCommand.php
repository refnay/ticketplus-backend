<?php

namespace App\Catalog\Event\Application\QuickUpdate\Status;

use App\Shared\Application\Input\PayloadMapper;

class QuickUpdateStatusEventCommand
{
    public function __construct(
        private string $id,
        private int $status,
    ) {}

    public static function create(string $id, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $id,
            $payload->int('status'),
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function status(): int
    {
        return $this->status;
    }
}
