<?php

namespace App\Sale\Ticket\Application\Search;

use App\Shared\Application\Security\AuthorizationContext;

class SearchTicketQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private TicketSearcher $searcher) {}

    public function __invoke(SearchTicketQuery $query): TicketsResponse
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