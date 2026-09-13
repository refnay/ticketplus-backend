<?php

namespace App\Catalog\Event\Application\QuickUpdate\Location;

use App\Shared\Application\Input\PayloadMapper;

class QuickUpdateLocationEventCommand
{
    public function __construct(
        private string $id,
        private ?string $venue,
        private string $location,
        private string $country,
        private string $city,
        private ?array $coordinates,
    ) {}

    public static function create(string $id, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $id,
            $payload->nullableString('venue'),
            $payload->string('location'),
            $payload->string('country'),
            $payload->string('city'),
            $payload->nullableArray('coordinates'),
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function venue(): ?string
    {
        return $this->venue;
    }

    public function location(): string
    {
        return $this->location;
    }

    public function country(): string
    {
        return $this->country;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function coordinates(): ?array
    {
        return $this->coordinates;
    }
}
