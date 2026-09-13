<?php

namespace App\Catalog\Event\Application\CreateDay;

use App\Catalog\Event\Domain\EventDayDate;
use App\Catalog\Event\Domain\EventDayDescription;
use App\Catalog\Event\Domain\EventDayEndTime;
use App\Catalog\Event\Domain\EventDaySaleStartsAt;
use App\Catalog\Event\Domain\EventDayStartTime;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class CreateEventDayCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private EventDayCreator $creator) {}

    public function __invoke(CreateEventDayCommand $command): string
    {
        $day = $command->day();

        return $this->creator->__invoke(
            EventId::fromString($command->id()),
            CompanyId::fromString($this->authorization->companyId()),
            EventDayDate::fromString($day->date()),
            EventDayStartTime::fromString($day->startTime()),
            EventDayEndTime::fromString($day->endTime()),
            EventDaySaleStartsAt::fromString($day->saleStartAt()),
            EventDayDescription::fromString($day->description()),
        );
    }
}
