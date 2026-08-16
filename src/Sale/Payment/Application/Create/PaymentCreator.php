<?php

namespace App\Sale\Payment\Application\Create;

use App\Sale\Order\Domain\Exceptions\OrderStatusNotAllowed;
use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Payment\Domain\Events\PaymentCreatedDomainEvent;
use App\Sale\Payment\Domain\Exceptions\PaymentAlreadyProcessing;
use App\Sale\Payment\Domain\Exceptions\PaymentNotFound;
use App\Sale\Payment\Domain\Payment;
use App\Sale\Payment\Domain\PaymentAmount;
use App\Sale\Payment\Domain\PaymentMethod;
use App\Sale\Payment\Domain\PaymentRepository;
use App\Sale\Payment\Domain\Services\PaymentProcessingFinder;
use App\Sale\Shared\Domain\UserId;
use App\Shared\Application\Messenger\EventBus;
use App\Shared\Domain\Utils\Primitive\ArrayBuilder;

class PaymentCreator
{
    private ArrayBuilder $events;

    public function __construct(
        private OrderFinder $orderFinder,
        private PaymentRepository $repository,
        private EventBus $eventBus,
        private PaymentProcessingFinder $paymentFinder,
    ) {
        $this->events = ArrayBuilder::generate();
    }

    public function __invoke(OrderId $orderId, PaymentMethod $method, UserId $userId): string
    {
        $order = $this->orderFinder->__invoke($orderId, $userId);

        if (!$order->status()->isPending()) {
            throw new OrderStatusNotAllowed();
        }

        try {
            $this->paymentFinder->__invoke($order->id());
            throw new PaymentAlreadyProcessing();
        } catch (PaymentNotFound) {
        }

        $payment = Payment::create(
            PaymentAmount::fromFloat($order->total()->value()),
            $method,
            $order->id(),
        );

        $this->repository->save($payment);

        $this->events->add(new PaymentCreatedDomainEvent($orderId->value(), $method->value(), $userId->value()));
        $this->eventBus->dispatch(...$this->events->items());

        return $payment->id()->value();
    }
}
