<?php

namespace App\Catalog\Event\Application\Delete;

use App\Shared\Application\Security\AuthorizationContext;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;

class DeleteEventCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private EventDeleter $deleter)
    {
    }

    public function __invoke(DeleteEventCommand $query): void
    {
        $companyId = $this->authorization->companyId();

        $this->deleter->__invoke(
            EventId::fromString($query->id()),
            CompanyId::fromString($companyId),
        );
    }
}