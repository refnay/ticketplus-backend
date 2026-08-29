<?php

namespace App\Tests\Sale\Order\Application\ApprovedSalesEvolutionReport;

use App\Sale\Order\Application\ApprovedSalesEvolutionReport\OrderReporter;
use App\Sale\Order\Domain\OrderCurrency;
use App\Sale\Order\Domain\OrderPaidAt;
use App\Sale\Order\Domain\OrderRepository;
use App\Sale\Shared\Domain\CompanyId;
use PHPUnit\Framework\TestCase;

final class OrderReporterTest extends TestCase
{
    public function testItCompletesDaysWithoutApprovedSales(): void
    {
        $repository = $this->createMock(OrderRepository::class);
        $repository
            ->expects(self::once())
            ->method('approvedSalesEvolution')
            ->willReturn([
                ['date' => '2026-08-01', 'amount' => 1200.00],
                ['date' => '2026-08-03', 'amount' => 950.00],
            ]);

        $response = (new OrderReporter($repository))->__invoke(
            OrderPaidAt::fromString('2026-08-01'),
            OrderPaidAt::fromString('2026-08-03'),
            OrderCurrency::fromString('PEN'),
            CompanyId::fromString('018f7c54-5f88-7e04-8a90-7af68c932555'),
        );

        self::assertSame([
            'from' => '01/08/2026',
            'to' => '03/08/2026',
            'currency' => 'PEN',
            'interval' => 'day',
            'sales' => [
                ['date' => '2026-08-01', 'amount' => 1200.00],
                ['date' => '2026-08-02', 'amount' => 0.00],
                ['date' => '2026-08-03', 'amount' => 950.00],
            ],
        ], $response->jsonSerialize());
    }

    public function testItGroupsAYearByMonthAndCompletesMonthsWithoutSales(): void
    {
        $repository = $this->createMock(OrderRepository::class);
        $repository
            ->expects(self::once())
            ->method('approvedSalesEvolution')
            ->willReturn([
                ['date' => '2026-01-01', 'amount' => 15200.00],
                ['date' => '2026-03-01', 'amount' => 18450.00],
            ]);

        $response = (new OrderReporter($repository))->__invoke(
            OrderPaidAt::fromString('2026-01-01'),
            OrderPaidAt::fromString('2026-12-31'),
            OrderCurrency::fromString('PEN'),
            CompanyId::fromString('018f7c54-5f88-7e04-8a90-7af68c932555'),
        )->jsonSerialize();

        self::assertSame('month', $response['interval']);
        self::assertCount(12, $response['sales']);
        self::assertSame(['date' => '2026-02-01', 'amount' => 0.00], $response['sales'][1]);
    }
}
