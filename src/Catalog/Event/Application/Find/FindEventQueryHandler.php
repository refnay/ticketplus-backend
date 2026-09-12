<?php

namespace App\Catalog\Event\Application\Find;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;

class FindEventQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private EventFinder $finder)
    {
    }

    public function __invoke(FindEventQuery $query): EventResponse
    {
        $companyId = $this->authorization->companyId();

        return $this->finder->__invoke(
            EventId::fromString($query->id()),
            CompanyId::fromString($companyId),
        );
    }
}