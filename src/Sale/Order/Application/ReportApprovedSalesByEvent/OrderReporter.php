<?php

namespace App\Sale\Order\Application\ReportApprovedSalesByEvent;

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
        int $limit,
    ): OrderByEventResponse {
        $events = $this->repository->approvedSalesByEvent(
            $companyId,
            $currency,
            $from,
            $to->add(new DateInterval('P1D')),
            $limit,
        );

        return new OrderByEventResponse(
            $from->asDMY(),
            $to->asDMY(),
            $currency->value(),
            $events,
        );
    }
}
