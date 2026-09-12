<?php

namespace App\Catalog\Seat\Application\Find;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Shared\Domain\CompanyId;

class FindSeatQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private SeatFinder $finder)
    {
    }

    public function __invoke(FindSeatQuery $query): SeatResponse
    {
        $companyId = $this->authorization->companyId();

        return $this->finder->__invoke(
            SeatId::fromString($query->id()),
            CompanyId::fromString($companyId),
        );
    }
}
