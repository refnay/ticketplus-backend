<?php

namespace App\Sale\Payment\Application\PaymentUpdatedEvent;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Payment\Application\Provider\PaymentProviderResolver;
use App\Sale\Payment\Domain\PaymentExternalReference;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Payment\Domain\PaymentRepository;
use App\Sale\Payment\Domain\PaymentStatus;
use App\Sale\Payment\Domain\Provider\ProviderList;
use App\Sale\Payment\Domain\Services\PaymentFinder;
use App\Sale\Shared\Domain\UserId;
use Throwable;

class PaymentProcessor
{
    public function __construct(
        private PaymentFinder $paymentFinder,
        private OrderFinder $orderFinder,
        private PaymentProviderResolver $resolver,
        private PaymentRepository $repository,
    ) {}

    public function __invoke(PaymentId $id, OrderId $orderId, UserId $userId, string $token): void
    {
        $order = $this->orderFinder->__invoke($orderId, $userId);
        $payment = $this->paymentFinder->__invoke($id, $order->id());

        $response = $this->resolver->__invoke(ProviderList::MERCADO_PAGO->value)->process($payment, $token);

        try {
            $payment->changeExternalReference(PaymentExternalReference::fromReference($response->id(), $response->provider()));
            $payment->changeStatus(PaymentStatus::fromInt($response->status()));
        } catch (Throwable) {
            $payment->changeExternalReference(PaymentExternalReference::fromNull());
            $payment->changeStatus(PaymentStatus::declined());
        }

        $this->repository->save($payment);
    }
}
