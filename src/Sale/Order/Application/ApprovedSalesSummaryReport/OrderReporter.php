<?php

namespace App\Sale\Order\Application\ApprovedSalesSummaryReport;

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
        $previousFrom = $from->sub(new DateInterval(sprintf('P%dD', $from->diffDays($auxiliaryTo))));

        $current = $this->repository->approvedSalesTotal($companyId, $currency, $from, $auxiliaryTo);
        $previous = $this->repository->approvedSalesTotal($companyId, $currency, $previousFrom, $from);

        return new OrderSummaryResponse(
            $from->asDMY(),
            $to->asDMY(),
            $previousFrom->asDMY(),
            $from->sub(new DateInterval('P1D'))->asDMY(),
            round($current, 2),
            $currency->value(),
            $this->variation($current, $previous),
        );
    }

    private function variation(float $current, float $previous): ?float
    {
        if ($previous === 0.00) {
            return null;
        }

        return round(($current - $previous) / $previous, 2);
    }
}
