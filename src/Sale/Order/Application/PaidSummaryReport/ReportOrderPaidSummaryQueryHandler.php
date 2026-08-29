<?php

namespace App\Sale\Order\Application\PaidSummaryReport;

use App\Sale\Order\Domain\OrderCurrency;
use App\Sale\Order\Domain\OrderPaidAt;
use App\Sale\Shared\Domain\CompanyId;
use App\Shared\Application\Security\AuthorizationContext;

class ReportOrderPaidSummaryQueryHandler
{
    public function __construct(
        private AuthorizationContext $authorization,
        private OrderReporter $reporter,
    ) {
    }

    public function __invoke(ReportOrderPaidSummaryQuery $query): OrderSummaryResponse
    {
        $this->authorization->requireAllPermissions();

        return $this->reporter->__invoke(
            OrderPaidAt::fromString($query->from()),
            OrderPaidAt::fromString($query->to()),
            OrderCurrency::fromString($query->currency())->toUpper(),
            CompanyId::fromString($this->authorization->requireCompanyId()),
        );
    }
}
