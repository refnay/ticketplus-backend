<?php

namespace App\Tests\Sale\Order\Application\ReportPaidCountSummary;

use App\Sale\Order\Application\ReportPaidCountSummary\OrderReporter;
use App\Sale\Order\Domain\OrderCurrency;
use App\Sale\Order\Domain\OrderPaidAt;
use App\Sale\Order\Domain\OrderRepository;
use App\Sale\Shared\Domain\CompanyId;
use PHPUnit\Framework\TestCase;

final class OrderReporterTest extends TestCase
{
    public function testItReportsPaidOrderCountAndAverageAmount(): void
    {
        $repository = $this->createMock(OrderRepository::class);
        $repository->expects(self::once())->method('countPaidOrders')->willReturn(1284);
        $repository->expects(self::once())->method('approvedSalesTotal')->willReturn(84230.00);

        $response = (new OrderReporter($repository))->__invoke(
            OrderPaidAt::fromString('2026-08-01'),
            OrderPaidAt::fromString('2026-08-31'),
            OrderCurrency::fromString('PEN'),
            CompanyId::fromString('018f7c54-5f88-7e04-8a90-7af68c932555'),
        );

        self::assertSame([
            'from' => '01/08/2026',
            'to' => '31/08/2026',
            'quantity' => 1284,
            'averageAmount' => 65.6,
            'currency' => 'PEN',
        ], $response->jsonSerialize());
    }

    public function testItReturnsZeroAverageWhenThereAreNoPaidOrders(): void
    {
        $repository = $this->createStub(OrderRepository::class);
        $repository->method('countPaidOrders')->willReturn(0);
        $repository->method('approvedSalesTotal')->willReturn(0.00);

        $response = (new OrderReporter($repository))->__invoke(
            OrderPaidAt::fromString('2026-08-01'),
            OrderPaidAt::fromString('2026-08-31'),
            OrderCurrency::fromString('PEN'),
            CompanyId::fromString('018f7c54-5f88-7e04-8a90-7af68c932555'),
        );

        self::assertSame(0.00, $response->jsonSerialize()['averageAmount']);
    }
}
