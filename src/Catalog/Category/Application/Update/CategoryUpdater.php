<?php

namespace App\Catalog\Category\Application\Update;

use App\Catalog\Category\Domain\Category;
use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Category\Domain\CategoryName;
use App\Catalog\Category\Domain\CategoryReference;
use App\Catalog\Category\Domain\CategoryRepository;
use App\Catalog\Category\Domain\Services\CategoryFinder;
use App\Catalog\Shared\Domain\CompanyId;

class CategoryUpdater
{
    public function __construct(private CategoryRepository $repository, private CategoryFinder $finder)
    {
    }

    public function __invoke(CategoryId $id, CategoryName $name, CategoryReference $reference, CompanyId $companyId): void
    {
        $category = $this->finder->__invoke($id, $companyId);

        $category->changeName($name);
        $category->changeReference($reference);

        $this->repository->update($category);
    }
}
