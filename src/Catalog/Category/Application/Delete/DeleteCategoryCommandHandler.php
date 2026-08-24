<?php

namespace App\Catalog\Category\Application\Delete;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Shared\Domain\CompanyId;

class DeleteCategoryCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private CategoryDeleter $deleter)
    {
    }

    public function __invoke(DeleteCategoryCommand $command): void
    {
        $this->authorization->requireAllPermissions();

        $this->deleter->__invoke(
            CategoryId::fromString($command->id()),
            CompanyId::fromString($this->authorization->requireCompanyId()),
        );
    }
}