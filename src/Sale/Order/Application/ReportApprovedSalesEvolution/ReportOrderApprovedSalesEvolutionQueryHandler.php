<?php

namespace App\Sale\Order\Application\ReportApprovedSalesEvolution;

use App\Sale\Order\Domain\OrderCurrency;
use App\Sale\Order\Domain\OrderPaidAt;
use App\Sale\Shared\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class ReportOrderApprovedSalesEvolutionQueryHandler
{
    public function __construct(
        private AuthorizationContext $authorization,
        private OrderReporter $reporter,
    ) {
    }

    public function __invoke(ReportOrderApprovedSalesEvolutionQuery $query): OrderEvolutionResponse
    {
        $companyId = $this->authorization->companyId();

        return $this->reporter->__invoke(
            OrderPaidAt::fromString($query->from()),
            OrderPaidAt::fromString($query->to()),
            OrderCurrency::fromString($query->currency()),
            CompanyId::fromString($companyId),
        );
    }
}
