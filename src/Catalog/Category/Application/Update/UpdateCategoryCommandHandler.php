<?php

namespace App\Catalog\Category\Application\Update;

use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Category\Domain\CategoryName;
use App\Catalog\Category\Domain\CategoryReference;
use App\Catalog\Shared\Domain\CompanyId;

class UpdateCategoryCommandHandler
{
    public function __construct(private CategoryUpdater $updater)
    {
    }

    public function __invoke(UpdateCategoryCommand $command): void
    {
        $this->updater->__invoke(
            CategoryId::fromString($command->id()),
            CategoryName::fromString($command->name()),
            CategoryReference::fromInt($command->reference()),
            CompanyId::fromString($command->session()->company()),
        );
    }
}