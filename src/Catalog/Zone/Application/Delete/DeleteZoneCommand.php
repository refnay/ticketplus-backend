<?php

namespace App\Catalog\Zone\Application\Delete;

use App\Shared\Application\Command\BaseCommand;

class DeleteZoneCommand extends BaseCommand
{
    public function __construct(private string $id, private string $event, private string $day)
    {
    }

    public static function create(string $id, string $event, string $day): self
    {
        return new self($id, $event, $day);
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
}