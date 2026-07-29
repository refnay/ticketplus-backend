<?php

namespace App\Catalog\Seat\Application\Update;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;

class UpdateSeatCommand extends BaseCommand
{
    public function __construct(
        private string $id,
        private string $event,
        private string $day,
        private string $zone,
        private string $code,
        private int $status,
    ) {
    }

    public static function create(string $id, string $event, string $day, string $zone, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $id,
            $event,
            $day,
            $zone,
            $payload->string('code'),
            $payload->int('status'),
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

    public function zone(): string
    {
        return $this->zone;
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