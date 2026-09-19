<?php

namespace App\Sale\Ticket\Application\ReportValidationSummary;

use App\Sale\Reference\Event\Domain\EventId;
use App\Sale\Reference\Event\Domain\Services\EventFinder;
use App\Sale\Reference\EventDay\Domain\EventDayId;
use App\Sale\Reference\EventDay\Domain\Exceptions\EventDayNotFound;
use App\Sale\Reference\EventDay\Domain\Services\EventDayFinder;
use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Ticket\Domain\TicketRepository;
use App\Shared\Domain\Utils\StringValue;

class TicketReporter
{
    public function __construct(
        private TicketRepository $repository,
        private EventFinder $eventFinder,
        private EventDayFinder $dayFinder,
    ) {}

    public function __invoke(
        EventId $eventId,
        EventDayId $dayId,
        CompanyId $companyId,
    ): TicketValidationSummaryResponse {
        $event = $this->eventFinder->__invoke($eventId, $companyId);
        $day = $this->dayFinder->__invoke($dayId);

        if (!StringValue::equals($day->eventId(), $event->id())) {
            throw new EventDayNotFound();
        }

        $summary = $this->repository->validationSummary($companyId, $eventId, $dayId);

        $validated = $summary['validated'];
        $pending = $summary['pending'];
        $total = $validated + $pending;

        return new TicketValidationSummaryResponse(
            $total,
            $validated,
            $pending,
            $total === 0 ? 0.0 : round(($validated / $total) * 100, 1),
        );
    }
}
