<?php

namespace App\Account\Company\Application\Find;

use App\Account\Company\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class FindCompanyQueryHandler
{
    public function __construct(
        private AuthorizationContext $authorization,
        private CompanyFinder $finder,
    ) {
    }

    public function __invoke(FindCompanyQuery $query): CompanyResponse
    {
        return $this->finder->__invoke(
            CompanyId::fromString($this->authorization->requireCompanyId()),
        );
    }
}
