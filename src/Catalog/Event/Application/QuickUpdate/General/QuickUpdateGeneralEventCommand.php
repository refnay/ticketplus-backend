<?php

namespace App\Catalog\Event\Application\QuickUpdate\General;

use App\Shared\Application\Input\PayloadMapper;

class QuickUpdateGeneralEventCommand
{
    public function __construct(
        private string $id,
        private string $name,
        private ?string $description,
        private string $category,
    ) {}

    public static function create(string $id, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $id,
            $payload->string('name'),
            $payload->nullableString('description'),
            $payload->string('category'),
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

    public function category(): string
    {
        return $this->category;
    }
}
