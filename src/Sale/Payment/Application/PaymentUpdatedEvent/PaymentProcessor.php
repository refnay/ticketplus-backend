<?php

namespace App\Sale\Payment\Application\PaymentUpdatedEvent;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Payment\Application\Resolver\PaymentProviderResolver;
use App\Sale\Payment\Domain\Events\PaymentWithEventApprovedDomainEvent;
use App\Sale\Payment\Domain\Events\PaymentWithOrderApprovedDomainEvent;
use App\Sale\Payment\Domain\Events\PaymentWithTicketApprovedDomainEvent;
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

        $this->repository->update($payment);

        if ($payment->status()->isApproved()) {
            $details = $order->details();
            foreach ($details->items() as $item) {
                $this->events->add(new PaymentWithEventApprovedDomainEvent(
                    $details->event(),
                    $details->day(),
                    $item['zone'],
                    $item['quantity'],
                    $item['seats'],
                ));
            }
            $this->events->add(new PaymentWithOrderApprovedDomainEvent(
                $orderId->value(),
                $userId->value(),
            ));
            $this->events->add(new PaymentWithTicketApprovedDomainEvent(
                $orderId->value(),
                $userId->value(),
            ));

            $this->eventBus->dispatch(...$this->events->items());
        }
    }
}
