<?php

namespace App\Sale\Order\Application\ApprovedSalesEvolutionReport;

use App\Sale\Order\Domain\OrderCurrency;
use App\Sale\Order\Domain\OrderPaidAt;
use App\Sale\Order\Domain\OrderRepository;
use App\Sale\Shared\Domain\CompanyId;
use App\Shared\Domain\Enums\ReportIntervalList;
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
    ): OrderEvolutionResponse {
        $auxiliaryTo = $to->add(new DateInterval('P1D'));
        $interval = $this->interval($from->diffDays($auxiliaryTo));
        $sales = $this->repository->approvedSalesEvolution(
            $companyId,
            $currency,
            $from,
            $auxiliaryTo,
            $interval,
        );

        return new OrderEvolutionResponse(
            $from->asDMY(),
            $to->asDMY(),
            $currency->value(),
            $interval,
            $this->completePeriod($from, $auxiliaryTo, $interval, $sales),
        );
    }

    private function completePeriod(
        OrderPaidAt $from,
        OrderPaidAt $to,
        string $interval,
        array $sales,
    ): array {
        $amounts = [];

        foreach ($sales as $sale) {
            $amounts[$sale['date']] = $sale['amount'];
        }

        $period = [];
        $point = $this->firstPoint($from, $interval);
        $step = $this->step($interval);

        while ($point->before($to)) {
            $date = $point->format('Y-m-d');
            $period[] = [
                'date' => $date,
                'amount' => $amounts[$date] ?? 0.00,
            ];
            $point = $point->add($step);
        }

        return $period;
    }

    private function interval(int $days): string
    {
        return match (true) {
            $days <= 31 => ReportIntervalList::DAY->value,
            $days <= 180 => ReportIntervalList::WEEK->value,
            default => ReportIntervalList::MONTH->value,
        };
    }

    private function firstPoint(OrderPaidAt $from, string $interval): OrderPaidAt
    {
        return match (ReportIntervalList::from($interval)) {
            ReportIntervalList::DAY => $from,
            ReportIntervalList::WEEK => $from->sub(
                new DateInterval(sprintf('P%dD', (int) $from->format('N') - 1)),
            ),
            ReportIntervalList::MONTH => OrderPaidAt::fromString($from->format('Y-m-01')),
        };
    }

    private function step(string $interval): DateInterval
    {
        return new DateInterval(match (ReportIntervalList::from($interval)) {
            ReportIntervalList::DAY => 'P1D',
            ReportIntervalList::WEEK => 'P7D',
            ReportIntervalList::MONTH => 'P1M',
        });
    }
}
