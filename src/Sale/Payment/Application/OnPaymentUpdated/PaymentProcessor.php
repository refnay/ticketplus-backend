<?php

namespace App\Sale\Payment\Application\OnPaymentUpdated;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Payment\Domain\Events\PaymentWithEventApprovedDomainEvent;
use App\Sale\Payment\Domain\Events\PaymentWithOrderApprovedDomainEvent;
use App\Sale\Payment\Domain\Events\PaymentWithTicketApprovedDomainEvent;
use App\Sale\Payment\Domain\PaymentExternalReference;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Payment\Domain\PaymentRepository;
use App\Sale\Payment\Domain\PaymentStatus;
use App\Sale\Payment\Domain\Gateway\TransactionGatewayList;
use App\Sale\Payment\Domain\Services\PaymentFinder;
use App\Sale\Reference\User\Domain\UserId;
use App\Shared\Application\Bus\EventBus;
use Throwable;

class PaymentProcessor
{
    public function __construct(
        private PaymentFinder $paymentFinder,
        private OrderFinder $orderFinder,
        private TransactionGatewayResolver $resolver,
        private PaymentRepository $repository,
        private EventBus $eventBus,
    ) {}

    public function __invoke(PaymentId $id, UserId $userId, string $token): void
    {
        $payment = $this->paymentFinder->__invoke($id);
        $orderId = $payment->orderId();
        $order = $this->orderFinder->__invoke($orderId, $userId);

        if ($payment->status()->isApproved() || $payment->status()->isDeclined()) {
            return;
        }

        try {
            $result = $this->resolver->__invoke(TransactionGatewayList::MERCADO_PAGO->value)->charge($payment, $token);

            $payment->changeStatus(PaymentStatus::fromInt($result->status()));
            $payment->changeExternalReference(PaymentExternalReference::fromReference($result->transactionId(), $result->gateway()));
        } catch (Throwable) {
            $payment->changeStatus(PaymentStatus::processing());
        }

        $this->repository->update($payment);

        if ($payment->status()->isApproved()) {
            $details = $order->details();
            $events = [];
            foreach ($details->items() as $item) {
                $events[] = new PaymentWithEventApprovedDomainEvent(
                    $details->event(),
                    $details->day(),
                    $item['zone'],
                    $item['quantity'],
                    $item['seats'],
                );
            }
            $events[] = new PaymentWithOrderApprovedDomainEvent(
                $orderId->value(),
                $userId->value(),
            );
            $events[] = new PaymentWithTicketApprovedDomainEvent(
                $orderId->value(),
                $userId->value(),
            );

            $this->eventBus->publish(...$events);
        }
    }
}
