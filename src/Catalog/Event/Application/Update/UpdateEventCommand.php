<?php

namespace App\Catalog\Event\Application\Update;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;
use App\Shared\Domain\Utils\Primitive\ArrayBuilder;

class UpdateEventCommand extends BaseCommand
{
    public function __construct(
        private string $id,
        private string $name,
        private ?string $description,
        private string $location,
        private string $country,
        private string $city,
        private string $category,
        private int $status,
        private array $days,
    ) {}

    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);
        $days = ArrayBuilder::generate();

        foreach ($payload->array('days') as $day) {
            $days->add(EventDayCommand::create($day));
        }

        return new self(
            $payload->string('id'),
            $payload->string('name'),
            $payload->nullableString('description'),
            $payload->string('location'),
            $payload->string('country'),
            $payload->string('city'),
            $payload->string('category'),
            $payload->int('status'),
            $days->items()
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

    public function category(): string
    {
        return $this->category;
    }

    public function status(): int
    {
        return $this->status;
    }

    public function days(): array
    {
        return $this->days;
    }
}
