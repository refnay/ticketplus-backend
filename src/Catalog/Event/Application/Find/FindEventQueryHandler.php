<?php

namespace App\Catalog\Event\Application\Find;

use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;

class FindEventQueryHandler
{
    public function __construct(private EventFinder $finder)
    {
    }

    public function __invoke(FindEventQuery $query): EventResponse
    {
        return $this->finder->__invoke(
            EventId::fromString($query->id()),
            CompanyId::fromString($query->session()->company()),
        );
    }
}