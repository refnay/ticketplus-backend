<?php

namespace App\Catalog\Category\Application\Find;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Category\Domain\CategoryId;

class FindCategoryQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private CategoryFinder $finder)
    {
    }

    public function __invoke(FindCategoryQuery $query): CategoryResponse
    {
        $this->authorization->requireAllPermissions();

        return $this->finder->__invoke(
            CategoryId::fromString($query->id()),
            CompanyId::fromString($this->authorization->requireCompanyId()),
        );
    }
}