<?php

namespace App\Catalog\Event\Application\DeleteDay;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class DeleteEventDayCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private EventDayDeleter $deleter) {}

    public function __invoke(DeleteEventDayCommand $command): void
    {
        $this->deleter->__invoke(
            EventId::fromString($command->id()),
            EventDayId::fromString($command->dayId()),
            CompanyId::fromString($this->authorization->companyId()),
        );
    }
}
