<?php

namespace App\Sale\Ticket\Application\FindByKey;

use App\Sale\Shared\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class FindTicketByKeyQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private TicketByKeyFinder $finder) {}

    public function __invoke(FindTicketByKeyQuery $query): TicketByKeyResponse
    {
        return $this->finder->__invoke(
            $query->key(),
            CompanyId::fromString($this->authorization->companyId()),
        );
    }
}