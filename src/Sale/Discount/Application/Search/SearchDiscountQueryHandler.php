<?php

namespace App\Sale\Discount\Application\Search;

use App\Shared\Application\Security\AuthorizationContext;

class SearchDiscountQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private DiscountSearcher $searcher)
    {
    }

    public function __invoke(SearchDiscountQuery $query): DiscountsResponse
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
