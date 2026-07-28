<?php

namespace App\Catalog\Event\Application\Delete;

use App\Shared\Application\Command\BaseCommand;

class DeleteEventCommand extends BaseCommand
{
    public function __construct(private string $id)
    {
    }

    public static function create(string $id): self
    {
        return new self($id);
    }

    public function id(): string
    {
        return $this->id;
    }
}
