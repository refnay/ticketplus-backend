<?php

namespace App\Sale\Order\Application\PaymentApprovedEvent;

use App\Sale\Order\Application\Port\ExchangeRate\ExchangeRateProvider;
use App\Sale\Order\Domain\OrderExchangeRate;
use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\OrderPaidAt;
use App\Sale\Order\Domain\OrderRepository;
use App\Sale\Order\Domain\OrderStatus;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Reference\User\Domain\UserId;
use App\Shared\Domain\Enums\CurrencyList;

class OrderUpdater
{
    public function __construct(
        private OrderRepository $repository,
        private OrderFinder $finder,
        private ExchangeRateProvider $exchangeRateProvider,
    ) {}

    public function __invoke(OrderId $id, UserId $userId): void
    {
        $order = $this->finder->__invoke($id, $userId);
        $exchangeRate = $this->exchangeRateProvider->rate(CurrencyList::USD->value, CurrencyList::PEN->value);

        $order->changeStatus(OrderStatus::paid());
        $order->changePaidAt(OrderPaidAt::now());
        $order->changeExchangeRate(OrderExchangeRate::fromFloat($exchangeRate));

        $this->repository->update($order);
    }
}
