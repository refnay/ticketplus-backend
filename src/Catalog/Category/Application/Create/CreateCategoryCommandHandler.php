<?php

namespace App\Catalog\Category\Application\Create;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Category\Domain\CategoryName;
use App\Catalog\Category\Domain\CategoryReference;
use App\Catalog\Shared\Domain\CompanyId;

class CreateCategoryCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private CategoryCreator $creator)
    {
    }

    public function __invoke(CreateCategoryCommand $command): string
    {
        $this->authorization->requireAllPermissions();

        return $this->creator->__invoke(
            CategoryName::fromString($command->name()),
            CategoryReference::fromInt($command->reference()),
            CompanyId::fromString($this->authorization->requireCompanyId()),
        );
    }
}