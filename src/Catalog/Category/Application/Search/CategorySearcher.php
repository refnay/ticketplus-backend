<?php

namespace App\Catalog\Category\Application\Search;

use App\Catalog\Category\Domain\Category;
use App\Catalog\Category\Domain\CategoryRepository;

class CategorySearcher
{
    public function __construct(private CategoryRepository $repository)
    {
    }

    public function __invoke(
        array $filters,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $offset,
    ): CategoriesResponse {
        $categories = $this->repository->searchByFilters($filters, $orderBy, $order, $limit, $offset);
        $total = $this->repository->countByFilters($filters);

        return new CategoriesResponse($total, ...array_map($this->makeResponse(), $categories));
    }

    private function makeResponse(): callable
    {
        return fn(Category $category) => CategoryResponse::create($category);
    }
}