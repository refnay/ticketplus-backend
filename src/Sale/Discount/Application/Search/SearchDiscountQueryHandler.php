<?php

namespace App\Sale\Discount\Application\Search;

use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Shared\Domain\EventId;

class SearchDiscountQueryHandler
{
    public function __construct(private DiscountSearcher $searcher)
    {
    }

    public function __invoke(SearchDiscountQuery $query): DiscountsResponse
    {
        return $this->searcher->__invoke(
            EventId::fromString($query->event()),
            CompanyId::fromString($query->company()),
            $query->filters(),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}
