<?php

namespace App\Sale\Order\Application\PaidSummaryReport;

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

    public function __invoke(OrderPaidAt $from, OrderPaidAt $to, OrderCurrency $currency, CompanyId $companyId): OrderSummaryResponse
    {
        $auxiliarTo = $to->add(new DateInterval('P1D'));

        $previousFrom = $from->sub(new DateInterval(sprintf('P%dD', $from->diffDays($auxiliarTo))));

        $current = $this->repository->amountPaidTotal($companyId, $currency, $from, $auxiliarTo);
        $previous = $this->repository->amountPaidTotal($companyId, $currency, $previousFrom, $from);

        return new OrderSummaryResponse(
            $from->format('d/m/Y'),
            $to->format('d/m/Y'),
            $previousFrom->format('d/m/Y'),
            $from->sub(new DateInterval('P1D'))->format('d/m/Y'),
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

        return round((($current - $previous) / $previous) * 100, 2);
    }
}
