<?php

namespace App\Catalog\Category\Application\Create;

use App\Catalog\Category\Domain\Category;
use App\Catalog\Category\Domain\CategoryName;
use App\Catalog\Category\Domain\CategoryReference;
use App\Catalog\Category\Domain\CategoryRepository;
use App\Catalog\Shared\Domain\CompanyId;

class CategoryCreator
{
    public function __construct(private CategoryRepository $repository)
    {
    }

    public function __invoke(CategoryName $name, CategoryReference $reference, CompanyId $companyId): string
    {
        $category = Category::create($name, $reference, $companyId);

        $this->repository->save($category);

        return $category->id()->value();
    }
}
