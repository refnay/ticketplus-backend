<?php

namespace App\Catalog\Category\Application\Delete;

use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Shared\Domain\CompanyId;

class DeleteCategoryCommandHandler
{
    public function __construct(private CategoryDeleter $deleter)
    {
    }

    public function __invoke(DeleteCategoryCommand $command): void
    {
        $this->deleter->__invoke(
            CategoryId::fromString($command->id()),
            CompanyId::fromString($command->session()->company()),
        );
    }
}