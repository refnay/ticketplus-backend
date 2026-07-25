<?php

namespace App\Catalog\Category\Application\Create;

use App\Catalog\Category\Domain\CategoryName;
use App\Catalog\Category\Domain\CategoryReference;

class CreateCategoryCommandHandler
{
    public function __construct(private CategoryCreator $creator)
    {
    }

    public function __invoke(CreateCategoryCommand $command): string
    {
        return $this->creator->__invoke(
            CategoryName::fromString($command->name()),
            CategoryReference::fromInt($command->reference()),
        );
    }
}