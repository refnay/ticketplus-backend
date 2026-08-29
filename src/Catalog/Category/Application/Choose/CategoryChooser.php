<?php

namespace App\Catalog\Category\Application\Choose;

use App\Catalog\Category\Domain\Category;
use App\Catalog\Category\Domain\CategoryRepository;

class CategoryChooser
{
    public function __construct(private CategoryRepository $repository)
    {
    }

    public function __invoke(string $companyId): CategoryChoicesResponse
    {
        $categories = $this->repository->searchByFilters(
            ['company' => $companyId],
            'name',
            'ASC',
            null,
            null,
        );

        return new CategoryChoicesResponse(
            ...array_map(
                static fn(Category $category): array => $category->toChooser(),
                $categories,
            ),
        );
    }
}
