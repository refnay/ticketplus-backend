<?php

namespace App\Catalog\Category\Domain\Services;

use App\Catalog\Category\Domain\Category;
use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Category\Domain\CategoryRepository;
use App\Catalog\Category\Domain\Exceptions\CategoryNotFound;
use App\Catalog\Shared\Domain\CompanyId;

class CategoryFinder
{
    public function __construct(private CategoryRepository $repository)
    {
    }

    public function __invoke(CategoryId $id, CompanyId $companyId): Category
    {
        $category = $this->repository->findById($id, $companyId);

        if (is_null($category)) {
            throw new CategoryNotFound();
        }

        return $category;
    }
}