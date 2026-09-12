<?php

namespace App\Tests\Sale\Ticket\Application\ReportSoldSummary;

use App\Sale\Order\Domain\OrderPaidAt;
use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Ticket\Application\ReportSoldSummary\TicketReporter;
use App\Sale\Ticket\Domain\TicketRepository;
use PHPUnit\Framework\TestCase;

final class TicketReporterTest extends TestCase
{
    public function testItReportsSoldTicketsAndPreviousPeriodVariation(): void
    {
        $repository = $this->createMock(TicketRepository::class);
        $repository
            ->expects(self::exactly(2))
            ->method('countSoldTickets')
            ->willReturnOnConsecutiveCalls(2946, 2714);

        $response = (new TicketReporter($repository))->__invoke(
            OrderPaidAt::fromString('2026-08-01'),
            OrderPaidAt::fromString('2026-08-31'),
            CompanyId::fromString('018f7c54-5f88-7e04-8a90-7af68c932555'),
        );

        self::assertSame([
            'from' => '01/08/2026',
            'to' => '31/08/2026',
            'previousFrom' => '01/07/2026',
            'previousTo' => '31/07/2026',
            'quantity' => 2946,
            'variation' => 0.09,
        ], $response->jsonSerialize());
    }
}
