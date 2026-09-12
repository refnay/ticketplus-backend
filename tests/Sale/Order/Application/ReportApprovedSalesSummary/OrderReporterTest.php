<?php

namespace App\Tests\Sale\Order\Application\ReportApprovedSalesSummary;

use App\Sale\Order\Application\ReportApprovedSalesSummary\OrderReporter;
use App\Sale\Order\Domain\OrderCurrency;
use App\Sale\Order\Domain\OrderPaidAt;
use App\Sale\Order\Domain\OrderRepository;
use App\Sale\Shared\Domain\CompanyId;
use PHPUnit\Framework\TestCase;

final class OrderReporterTest extends TestCase
{
    public function testItReportsApprovedSalesAndPreviousPeriodVariation(): void
    {
        $repository = $this->createMock(OrderRepository::class);
        $repository
            ->expects(self::exactly(2))
            ->method('approvedSalesTotal')
            ->willReturnOnConsecutiveCalls(84230.00, 73756.57);

        $response = (new OrderReporter($repository))->__invoke(
            OrderPaidAt::fromString('2026-08-01'),
            OrderPaidAt::fromString('2026-08-31'),
            OrderCurrency::fromString('PEN'),
            CompanyId::fromString('018f7c54-5f88-7e04-8a90-7af68c932555'),
        );

        self::assertSame([
            'from' => '01/08/2026',
            'to' => '31/08/2026',
            'previousFrom' => '01/07/2026',
            'previousTo' => '31/07/2026',
            'amount' => 84230.00,
            'currency' => 'PEN',
            'variation' => 0.14,
        ], $response->jsonSerialize());
    }
}
