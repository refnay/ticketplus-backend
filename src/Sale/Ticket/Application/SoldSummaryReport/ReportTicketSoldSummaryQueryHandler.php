<?php

namespace App\Sale\Ticket\Application\SoldSummaryReport;

use App\Sale\Order\Domain\OrderPaidAt;
use App\Sale\Shared\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class ReportTicketSoldSummaryQueryHandler
{
    public function __construct(
        private AuthorizationContext $authorization,
        private TicketReporter $reporter,
    ) {
    }

    public function __invoke(ReportTicketSoldSummaryQuery $query): TicketSummaryResponse
    {
        $this->authorization->requireAllPermissions();

        return $this->reporter->__invoke(
            OrderPaidAt::fromString($query->from()),
            OrderPaidAt::fromString($query->to()),
            CompanyId::fromString($this->authorization->requireCompanyId()),
        );
    }
}
