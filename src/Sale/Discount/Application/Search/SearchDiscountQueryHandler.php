<?php

namespace App\Sale\Discount\Application\Search;

use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Event\Domain\EventId;
use App\Shared\Application\Security\AuthorizationContext;

class SearchDiscountQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private DiscountSearcher $searcher)
    {
    }

    public function __invoke(SearchDiscountQuery $query): DiscountsResponse
    {
        $this->authorization->requireAllPermissions();

        return $this->searcher->__invoke(
            EventId::fromString($query->event()),
            CompanyId::fromString($this->authorization->requireCompanyId()),
            $query->filters(),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}
