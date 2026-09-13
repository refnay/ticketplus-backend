<?php

namespace App\Catalog\Event\Application\UpdateDay;

use App\Catalog\Event\Domain\EventDayDate;
use App\Catalog\Event\Domain\EventDayDescription;
use App\Catalog\Event\Domain\EventDayEndTime;
use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventDaySaleStartsAt;
use App\Catalog\Event\Domain\EventDayStartTime;
use App\Catalog\Event\Domain\EventDayStatus;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class UpdateEventDayCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private EventDayUpdater $updater) {}

    public function __invoke(UpdateEventDayCommand $command): void
    {
        $day = $command->day();

        $this->updater->__invoke(
            EventId::fromString($command->id()),
            EventDayId::fromString($command->dayId()),
            CompanyId::fromString($this->authorization->companyId()),
            EventDayDate::fromString($day->date()),
            EventDayStartTime::fromString($day->startTime()),
            EventDayEndTime::fromString($day->endTime()),
            EventDaySaleStartsAt::fromString($day->saleStartAt()),
            EventDayDescription::fromString($day->description()),
            EventDayStatus::fromInt($day->status()),
        );
    }
}
