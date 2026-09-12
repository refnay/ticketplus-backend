<?php

namespace App\Catalog\Zone\Application\Update;

use App\Shared\Application\Input\PayloadMapper;

class UpdateZoneCommand
{
    public function __construct(
        private string $id,
        private string $name,
        private float $price,
        private int $total,
        private int $hierarchy,
        private bool $numberedSeating,
    ) {
    }

    public static function create(string $id, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $id,
            $payload->string('name'),
            $payload->float('price'),
            $payload->int('total'),
            $payload->int('hierarchy'),
            $payload->bool('numberedSeating'),
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function total(): int
    {
        return $this->total;
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
