<?php

namespace App\Sale\Order\Application\PaidCountSummaryReport;

use App\Sale\Order\Domain\OrderCurrency;
use App\Sale\Order\Domain\OrderPaidAt;
use App\Sale\Order\Domain\OrderRepository;
use App\Sale\Shared\Domain\CompanyId;
use DateInterval;

class OrderReporter
{
    public function __construct(private OrderRepository $repository)
    {
    }

    public function __invoke(
        OrderPaidAt $from,
        OrderPaidAt $to,
        OrderCurrency $currency,
        CompanyId $companyId,
    ): OrderSummaryResponse {
        $auxiliaryTo = $to->add(new DateInterval('P1D'));
        $quantity = $this->repository->countPaidOrders($companyId, $from, $auxiliaryTo);
        $amount = $this->repository->approvedSalesTotal($companyId, $currency, $from, $auxiliaryTo);

        return new OrderSummaryResponse(
            $from->asDMY(),
            $to->asDMY(),
            $quantity,
            $quantity === 0 ? 0.00 : round($amount / $quantity, 2),
            $currency->value(),
        );
    }
}
