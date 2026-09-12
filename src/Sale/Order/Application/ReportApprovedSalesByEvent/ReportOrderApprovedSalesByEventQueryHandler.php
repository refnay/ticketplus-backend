<?php

namespace App\Sale\Order\Application\ReportApprovedSalesByEvent;

use App\Sale\Order\Domain\OrderCurrency;
use App\Sale\Order\Domain\OrderPaidAt;
use App\Sale\Shared\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class ReportOrderApprovedSalesByEventQueryHandler
{
    public function __construct(
        private AuthorizationContext $authorization,
        private OrderReporter $reporter,
    ) {
    }

    public function __invoke(ReportOrderApprovedSalesByEventQuery $query): OrderByEventResponse
    {
        $companyId = $this->authorization->companyId();

        return $this->reporter->__invoke(
            OrderPaidAt::fromString($query->from()),
            OrderPaidAt::fromString($query->to()),
            OrderCurrency::fromString($query->currency()),
            CompanyId::fromString($companyId),
            $query->limit(),
        );
    }
}
