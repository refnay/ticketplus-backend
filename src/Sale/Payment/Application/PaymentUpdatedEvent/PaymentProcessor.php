<?php

namespace App\Sale\Payment\Application\PaymentUpdatedEvent;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Payment\Application\Resolver\PaymentProviderResolver;
use App\Sale\Payment\Domain\Events\PaymentProcessedDomainEvent;
use App\Sale\Payment\Domain\PaymentExternalReference;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Payment\Domain\PaymentRepository;
use App\Sale\Payment\Domain\PaymentStatus;
use App\Sale\Payment\Domain\Provider\PaymentProviderList;
use App\Sale\Payment\Domain\Services\PaymentFinder;
use App\Sale\Shared\Domain\UserId;
use App\Shared\Application\Messenger\EventBus;
use App\Shared\Domain\Utils\Primitive\ArrayBuilder;
use Throwable;

class PaymentProcessor
{
    private ArrayBuilder $events;

    public function __construct(
        private PaymentFinder $paymentFinder,
        private OrderFinder $orderFinder,
        private PaymentProviderResolver $resolver,
        private PaymentRepository $repository,
        private EventBus $eventBus,
    ) {
        $this->events = ArrayBuilder::generate();
    }

    public function __invoke(PaymentId $id, OrderId $orderId, UserId $userId, string $token): void
    {
        $order = $this->orderFinder->__invoke($orderId, $userId);
        $payment = $this->paymentFinder->__invoke($id, $order->id());

        try {
            $response = $this->resolver->__invoke(PaymentProviderList::MERCADO_PAGO->value)->process($payment, $token);

            $payment->changeStatus(PaymentStatus::fromInt($response->status()));
            $payment->changeExternalReference(PaymentExternalReference::fromReference($response->id(), $response->provider()));
        } catch (Throwable) {
            $payment->changeStatus(PaymentStatus::processing());
        }

        $this->repository->save($payment);

        if ($payment->status()->isApproved()) {
            $this->events->add(new PaymentProcessedDomainEvent(
                $order->details()->event(),
                $order->details()->zone(),
                $order->details()->day(),
                $order->details()->quantity(),
                $payment->status()->value(),
                $order->details()->seats(),
            ));
        }

        $this->eventBus->dispatch(...$this->events->items());
    }
}
