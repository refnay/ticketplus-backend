<?php

namespace App\Sale\Order\Infrastructure\Persistence;

use App\Sale\Order\Domain\Order;
use App\Sale\Order\Domain\OrderCurrency;
use App\Sale\Order\Domain\OrderExpiresAt;
use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\OrderPaymentMethod;
use App\Sale\Order\Domain\OrderStatus;
use App\Sale\Order\Domain\OrderSubTotal;
use App\Sale\Order\Domain\OrderTax;
use App\Sale\Order\Domain\OrderTotal;
use App\Sale\Shared\Domain\UserId;
use App\Shared\Infrastructure\Persistence\Entity\Purchase as OrderEntity;

class OrderMapper
{
    public function __construct(private RelationFetcher $fetcher) {}

    public function newEntity(Order $order): OrderEntity
    {
        $entity = new OrderEntity();

        $entity->setId($order->id()->toUuid());
        $entity->setSubTotal($order->subTotal()->value());
        $entity->setTax($order->tax()->value());
        $entity->setTotal($order->total()->value());
        $entity->setCurrency($order->currency()->value());
        $entity->setStatus($order->status()->value());
        $entity->setPaymentMethod($order->paymentMethod()->value());
        $entity->setExpiresdAt($order->expiresAt()->value());
        $entity->setAttendee($this->fetcher->user($order->userId()));

        if (!is_null($order->discountId())) {
            $entity->setDiscount($this->fetcher->discount($order->discountId()));
        }

        return $entity;
    }

    public function newDomain(OrderEntity $entity): Order
    {
        $order = new Order(
            OrderId::fromString($entity->getId()),
            OrderCurrency::fromString($entity->getCurrency()),
            OrderPaymentMethod::fromInt($entity->getPaymentMethod()),
            OrderStatus::fromInt($entity->getStatus()),
            OrderSubTotal::fromFloat($entity->getSubTotal()),
            OrderTax::fromFloat($entity->getTax()),
            OrderTotal::fromFloat($entity->getTotal()),
            OrderExpiresAt::fromDateTime($entity->getExpiresAt()),
            UserId::fromString($entity->getAttendee()->getId())
        );
        $order->changeDiscountId($entity->getDiscount()?->getId());

        return $order;
    }

    public function update(OrderEntity $entity, Order $order): void
    {
        $entity->setSubTotal($order->subTotal()->value());
        $entity->setTax($order->tax()->value());
        $entity->setTotal($order->total()->value());
        $entity->setCurrency($order->currency()->value());
        $entity->setStatus($order->status()->value());
        $entity->setPaymentMethod($order->paymentMethod()->value());
    }

    public function entityClass(): string
    {
        return OrderEntity::class;
    }
}
