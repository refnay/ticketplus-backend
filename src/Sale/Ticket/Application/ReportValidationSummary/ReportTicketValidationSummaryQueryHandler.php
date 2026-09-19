<?php

namespace App\Sale\Ticket\Application\ReportValidationSummary;

use App\Sale\Reference\Event\Domain\EventId;
use App\Sale\Reference\EventDay\Domain\EventDayId;
use App\Sale\Shared\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class ReportTicketValidationSummaryQueryHandler
{
    public function __construct(
        private AuthorizationContext $authorization,
        private TicketReporter $reporter,
    ) {}

    public function __invoke(ReportTicketValidationSummaryQuery $query): TicketValidationSummaryResponse
    {
        return $this->reporter->__invoke(
            EventId::fromString($query->event()),
            EventDayId::fromString($query->day()),
            CompanyId::fromString($this->authorization->companyId()),
        );
    }
}
