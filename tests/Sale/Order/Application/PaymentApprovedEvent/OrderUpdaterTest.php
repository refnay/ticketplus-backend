<?php

namespace App\Tests\Sale\Order\Application\PaymentApprovedEvent;

use App\Sale\Order\Application\PaymentApprovedEvent\OrderUpdater;
use App\Sale\Order\Application\Port\ExchangeRate\ExchangeRateProvider;
use App\Sale\Order\Domain\Order;
use App\Sale\Order\Domain\OrderCurrency;
use App\Sale\Order\Domain\OrderDetails;
use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\OrderPrice;
use App\Sale\Order\Domain\OrderRepository;
use App\Sale\Order\Domain\OrderStatusList;
use App\Sale\Order\Domain\OrderSubTotal;
use App\Sale\Order\Domain\OrderTax;
use App\Sale\Order\Domain\OrderTotal;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Reference\Event\Domain\EventId;
use App\Sale\Reference\User\Domain\UserId;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class OrderUpdaterTest extends TestCase
{
    #[DataProvider('orderCurrencies')]
    public function testItStoresTheUsdToPenExchangeRateWhenPaymentIsApproved(string $orderCurrency): void
    {
        $orderId = OrderId::fromString('018f7c54-5f88-7e04-8a90-7af68c932555');
        $userId = UserId::fromString('018f7c54-5f88-7e04-8a90-7af68c932556');
        $order = Order::create(
            OrderCurrency::fromString($orderCurrency),
            OrderPrice::fromFloat(100.00),
            OrderSubTotal::fromFloat(100.00),
            OrderTax::fromFloat(18.00),
            OrderTotal::fromFloat(118.00),
            OrderDetails::fromPattern(
                '018f7c54-5f88-7e04-8a90-7af68c932557',
                '018f7c54-5f88-7e04-8a90-7af68c932558',
                [],
            ),
            EventId::fromString('018f7c54-5f88-7e04-8a90-7af68c932557'),
            $userId,
        );

        $finder = $this->createStub(OrderFinder::class);
        $finder->method('__invoke')->willReturn($order);

        $exchangeRateProvider = $this->createMock(ExchangeRateProvider::class);
        $exchangeRateProvider
            ->expects(self::once())
            ->method('rate')
            ->with('USD', 'PEN')
            ->willReturn(3.53);

        $repository = $this->createMock(OrderRepository::class);
        $repository
            ->expects(self::once())
            ->method('update')
            ->with(self::callback(static function (Order $updatedOrder): bool {
                self::assertSame(OrderStatusList::PAID->value, $updatedOrder->status()->value());
                self::assertSame(3.53, $updatedOrder->exchangeRate()->value());
                self::assertFalse($updatedOrder->paidAt()->isNull());

                return true;
            }));

        $updater = new OrderUpdater($repository, $finder, $exchangeRateProvider);

        $updater->__invoke($orderId, $userId);
    }

    public static function orderCurrencies(): array
    {
        return [
            'USD order' => ['USD'],
            'PEN order' => ['PEN'],
        ];
    }
}
