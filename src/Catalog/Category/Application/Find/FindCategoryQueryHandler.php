<?php

namespace App\Catalog\Category\Application\Find;

use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Category\Domain\CategoryId;

class FindCategoryQueryHandler
{
    public function __construct(private CategoryFinder $finder)
    {
    }

    public function __invoke(FindCategoryQuery $query): CategoryResponse
    {
        return $this->finder->__invoke(
            CategoryId::fromString($query->id()),
            CompanyId::fromString($query->session()->company()),
        );
    }
}