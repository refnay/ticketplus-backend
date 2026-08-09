<?php

namespace App\Sale\Order\Domain;

use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Shared\Domain\UserId;

class Order
{
    private OrderId $id;
    private OrderCurrency $currency;
    private OrderPaymentMethod $paymentMethod;
    private OrderStatus $status;
    private OrderSubTotal $subTotal;
    private OrderTax $tax;
    private OrderTotal $total;
    private UserId $userId;
    private ?DiscountId $discountId = null;

    public function __construct(
        OrderId $id,
        OrderCurrency $currency,
        OrderPaymentMethod $paymentMethod,
        OrderStatus $status,
        OrderSubTotal $subTotal,
        OrderTax $tax,
        OrderTotal $total,
        UserId $userId,
    ) {
        $this->id = $id;
        $this->currency = $currency;
        $this->paymentMethod = $paymentMethod;
        $this->status = $status;
        $this->subTotal = $subTotal;
        $this->tax = $tax;
        $this->total = $total;
        $this->userId = $userId;
    }

    public static function create(
        OrderCurrency $currency,
        OrderPaymentMethod $paymentMethod,
        OrderStatus $status,
        OrderSubTotal $subTotal,
        OrderTax $tax,
        OrderTotal $total,
        UserId $userId,
    ): self {
        return new self(
            OrderId::generate(),
            $currency,
            $paymentMethod,
            $status,
            $subTotal,
            $tax,
            $total,
            $userId,
        );
    }

    public function id(): OrderId
    {
        return $this->id;
    }

    public function currency(): OrderCurrency
    {
        return $this->currency;
    }

    public function paymentMethod(): OrderPaymentMethod
    {
        return $this->paymentMethod;
    }

    public function status(): OrderStatus
    {
        return $this->status;
    }

    public function subTotal(): OrderSubTotal
    {
        return $this->subTotal;
    }

    public function tax(): OrderTax
    {
        return $this->tax;
    }

    public function total(): OrderTotal
    {
        return $this->total;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function discountId(): ?DiscountId
    {
        return $this->discountId;
    }

    public function changeCurrency(OrderCurrency $currency): void
    {
        $this->currency = $currency;
    }

    public function changePaymentMethod(OrderPaymentMethod $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function changeStatus(OrderStatus $status): void
    {
        $this->status = $status;
    }

    public function changeSubTotal(OrderSubTotal $subTotal): void
    {
        $this->subTotal = $subTotal;
    }

    public function changeTax(OrderTax $tax): void
    {
        $this->tax = $tax;
    }

    public function changeTotal(OrderTotal $total): void
    {
        $this->total = $total;
    }

    public function changeDiscountId(?DiscountId $discountId): void
    {
        $this->discountId = $discountId;
    }
}
