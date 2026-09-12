<?php

namespace App\Catalog\Seat\Application\BulkCreate;

use App\Shared\Application\Input\PayloadMapper;
use App\Shared\Application\Support\ArrayBuilder;

class BulkCreateSeatCommand
{
    public function __construct(
        private string $zone,
        private array $seats,
    ) {
    }

    public static function create(string $zone, array $data): self
    {
        $payload = PayloadMapper::fromData($data);
        $seats = ArrayBuilder::generate();

        foreach ($payload->array('seats') as $seat) {
            $seats->add($seat);
        }

        return new self(
            $zone,
            $seats->items(),
        );
    }

    public function zone(): string
    {
        return $this->zone;
    }

    public function seats(): array
    {
        return $this->seats;
    }
}
