<?php

namespace App\Catalog\Category\Application\List;

class ListCategoryQueryHandler
{
    public function __construct(private CategoryLister $lister)
    {
    }

    public function __invoke(ListCategoryQuery $query): CategoriesResponse
    {
        return $this->lister->__invoke(
            $query->filters(),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}