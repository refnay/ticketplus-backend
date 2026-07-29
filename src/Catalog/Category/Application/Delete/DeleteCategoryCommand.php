<?php

namespace App\Catalog\Category\Application\Delete;

use App\Shared\Application\Command\BaseCommand;

class DeleteCategoryCommand extends BaseCommand
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