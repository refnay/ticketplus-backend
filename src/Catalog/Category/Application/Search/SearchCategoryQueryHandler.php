<?php

namespace App\Catalog\Category\Application\Search;

use App\Shared\Application\Security\AuthorizationContext;

class SearchCategoryQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private CategorySearcher $searcher)
    {
    }

    public function __invoke(SearchCategoryQuery $query): CategoriesResponse
    {
        $companyId = $this->authorization->companyId();

        return $this->searcher->__invoke(
            $query->filters($companyId),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}
