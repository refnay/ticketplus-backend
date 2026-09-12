<?php

namespace App\Sale\Ticket\Application\ReportSoldSummary;

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
        $companyId = $this->authorization->companyId();

        return $this->reporter->__invoke(
            OrderPaidAt::fromString($query->from()),
            OrderPaidAt::fromString($query->to()),
            CompanyId::fromString($companyId),
        );
    }
}
