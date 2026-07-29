<?php

namespace App\Catalog\Category\Application\Delete;

use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Category\Domain\CategoryRepository;
use App\Catalog\Category\Domain\Services\CategoryFinder;
use App\Catalog\Shared\Domain\CompanyId;

class CategoryDeleter
{
    public function __construct(private CategoryRepository $repository, private CategoryFinder $finder)
    {
    }

    public function __invoke(CategoryId $id, CompanyId $companyId): void
    {
        $category = $this->finder->__invoke($id, $companyId);

        $this->repository->delete($category);
    }
}
