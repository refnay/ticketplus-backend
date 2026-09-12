<?php

namespace App\Sale\Ticket\Application\ReportSoldSummary;

use App\Sale\Order\Domain\OrderPaidAt;
use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Ticket\Domain\TicketRepository;
use DateInterval;

class TicketReporter
{
    public function __construct(private TicketRepository $repository)
    {
    }

    public function __invoke(
        OrderPaidAt $from,
        OrderPaidAt $to,
        CompanyId $companyId,
    ): TicketSummaryResponse {
        $auxiliaryTo = $to->add(new DateInterval('P1D'));
        $previousFrom = $from->sub(new DateInterval(sprintf('P%dD', $from->diffDays($auxiliaryTo))));

        $current = $this->repository->countSoldTickets($companyId, $from, $auxiliaryTo);
        $previous = $this->repository->countSoldTickets($companyId, $previousFrom, $from);

        return new TicketSummaryResponse(
            $from->asDMY(),
            $to->asDMY(),
            $previousFrom->asDMY(),
            $from->sub(new DateInterval('P1D'))->asDMY(),
            $current,
            $this->variation($current, $previous),
        );
    }

    private function variation(int $current, int $previous): ?float
    {
        if ($previous === 0) {
            return null;
        }

        return round(($current - $previous) / $previous, 2);
    }
}
