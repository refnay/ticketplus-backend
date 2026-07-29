<?php

namespace App\Catalog\Category\Application\Find;

use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Category\Domain\Services\CategoryFinder as ServicesCategoryFinder;
use App\Catalog\Category\Domain\CategoryId;

class CategoryFinder
{
    public function __construct(private ServicesCategoryFinder $finder)
    {
    }

    public function __invoke(CategoryId $id, CompanyId $companyId): CategoryResponse
    {
        $categoty = $this->finder->__invoke($id, $companyId);

        return CategoryResponse::create($categoty);
    }
}
