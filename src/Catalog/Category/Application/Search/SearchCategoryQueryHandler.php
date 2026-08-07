<?php

namespace App\Catalog\Category\Application\Search;

class SearchCategoryQueryHandler
{
    public function __construct(private CategorySearcher $searcher)
    {
    }

    public function __invoke(SearchCategoryQuery $query): CategoriesResponse
    {
        return $this->searcher->__invoke(
            $query->filters(),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}