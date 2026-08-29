<?php

namespace App\Tests\Sale\Order\Application\ApprovedSalesByEventReport;

use App\Sale\Order\Application\ApprovedSalesByEventReport\OrderReporter;
use App\Sale\Order\Domain\OrderCurrency;
use App\Sale\Order\Domain\OrderPaidAt;
use App\Sale\Order\Domain\OrderRepository;
use App\Sale\Shared\Domain\CompanyId;
use PHPUnit\Framework\TestCase;

final class OrderReporterTest extends TestCase
{
    public function testItReportsApprovedSalesGroupedByEvent(): void
    {
        $events = [
            [
                'id' => '018f7c54-5f88-7e04-8a90-7af68c932551',
                'name' => 'Festival Lima Sound 2026',
                'amount' => 42600.00,
            ],
            [
                'id' => '018f7c54-5f88-7e04-8a90-7af68c932552',
                'name' => 'Conferencia Tech Perú 2026',
                'amount' => 24500.00,
            ],
        ];

        $repository = $this->createMock(OrderRepository::class);
        $repository
            ->expects(self::once())
            ->method('approvedSalesByEvent')
            ->with(
                self::isInstanceOf(CompanyId::class),
                self::isInstanceOf(OrderCurrency::class),
                self::isInstanceOf(OrderPaidAt::class),
                self::isInstanceOf(OrderPaidAt::class),
                4,
            )
            ->willReturn($events);

        $response = (new OrderReporter($repository))->__invoke(
            OrderPaidAt::fromString('2026-08-01'),
            OrderPaidAt::fromString('2026-08-31'),
            OrderCurrency::fromString('PEN'),
            CompanyId::fromString('018f7c54-5f88-7e04-8a90-7af68c932555'),
        );

        self::assertSame([
            'from' => '01/08/2026',
            'to' => '31/08/2026',
            'currency' => 'PEN',
            'events' => $events,
        ], $response->jsonSerialize());
    }
}
