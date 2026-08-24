<?php

namespace App\Catalog\Event\Application\Create;

use App\Shared\Application\Input\PayloadMapper;
use App\Shared\Application\Support\ArrayBuilder;

class CreateEventCommand
{
    public function __construct(
        private string $name,
        private ?string $description,
        private string $location,
        private string $country,
        private string $city,
        private string $currency,
        private float $taxRate,
        private string $category,
        private array $days,
    ) {}

    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);
        $days = ArrayBuilder::generate();

        foreach ($payload->array('days') as $day) {
            $days->add(EventDayCommand::create($day));
        }

        $days->removeDuplicates();

        return new self(
            $payload->string('name'),
            $payload->nullableString('description'),
            $payload->string('location'),
            $payload->string('country'),
            $payload->string('city'),
            $payload->string('currency'),
            $payload->float('taxRate'),
            $payload->string('category'),
            $days->items()
        );
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
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

    public function currency(): string
    {
        return $this->currency;
    }

    public function taxRate(): float
    {
        return $this->taxRate;
    }

    public function category(): string
    {
        return $this->category;
    }
    
    public function days(): array
    {
        return $this->days;
    }
}
