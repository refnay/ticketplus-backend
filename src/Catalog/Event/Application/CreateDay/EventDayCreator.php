<?php

namespace App\Catalog\Event\Application\CreateDay;

use App\Catalog\Event\Domain\EventDay;
use App\Catalog\Event\Domain\EventDayDate;
use App\Catalog\Event\Domain\EventDayDescription;
use App\Catalog\Event\Domain\EventDayEndTime;
use App\Catalog\Event\Domain\EventDaySaleStartsAt;
use App\Catalog\Event\Domain\EventDayStartTime;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Shared\Domain\CompanyId;

class EventDayCreator
{
    public function __construct(private EventRepository $repository, private EventFinder $eventFinder) {}

    public function __invoke(
        EventId $id,
        CompanyId $companyId,
        EventDayDate $date,
        EventDayStartTime $startTime,
        EventDayEndTime $endTime,
        EventDaySaleStartsAt $saleStartAt,
        EventDayDescription $description,
    ): string {
        $event = $this->eventFinder->__invoke($id, $companyId);
        $day = EventDay::create($date, $startTime, $endTime, $saleStartAt, $description, $event);
        $event->addDay($day);

        $this->repository->update($event);

        return $day->id()->toUuid()->toRfc4122();
    }
}
