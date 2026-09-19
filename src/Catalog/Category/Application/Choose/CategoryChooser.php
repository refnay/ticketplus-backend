<?php

namespace App\Catalog\Category\Application\Choose;

use App\Catalog\Category\Domain\Category;
use App\Catalog\Category\Domain\CategoryRepository;

class CategoryChooser
{
    public function __construct(private CategoryRepository $repository) {}

    public function __invoke(
        array $filters,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $offset,
    ): CategoryChoicesResponse {
        $categories = $this->repository->searchByFilters(
            $filters,
            $orderBy,
            $order,
            $limit,
            $offset,
        );

        return new CategoryChoicesResponse(
            ...array_map(
                static fn(Category $category): array => $category->toChooser(),
                $categories,
            ),
        );
    }
}
