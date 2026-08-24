<?php

namespace App\Sale\Order\Domain;

use App\Sale\Discount\Domain\DiscountId;
use App\Sale\User\Domain\UserId;

class Order
{
    private OrderId $id;
    private OrderCurrency $currency;
    private OrderPaymentMethod $paymentMethod;
    private OrderStatus $status;
    private OrderPrice $price;
    private OrderSubTotal $subTotal;
    private OrderTax $tax;
    private OrderTotal $total;
    private OrderExpiresAt $expiresAt;
    private OrderDetails $details;
    private UserId $userId;
    private ?DiscountId $discountId = null;

    public function __construct(
        OrderId $id,
        OrderCurrency $currency,
        OrderPaymentMethod $paymentMethod,
        OrderStatus $status,
        OrderPrice $price,
        OrderSubTotal $subTotal,
        OrderTax $tax,
        OrderTotal $total,
        OrderExpiresAt $expiresAt,
        OrderDetails $details,
        UserId $userId,
    ) {
        $this->id = $id;
        $this->currency = $currency;
        $this->paymentMethod = $paymentMethod;
        $this->status = $status;
        $this->price = $price;
        $this->subTotal = $subTotal;
        $this->tax = $tax;
        $this->total = $total;
        $this->expiresAt = $expiresAt;
        $this->details = $details;
        $this->userId = $userId;
    }

    public static function create(
        OrderCurrency $currency,
        OrderPrice $price,
        OrderSubTotal $subTotal,
        OrderTax $tax,
        OrderTotal $total,
        OrderDetails $details,
        UserId $userId,
    ): self {
        return new self(
            OrderId::generate(),
            $currency,
            OrderPaymentMethod::undefined(),
            OrderStatus::pending(),
            $price,
            $subTotal,
            $tax,
            $total,
            OrderExpiresAt::start(),
            $details,
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

    public function price(): OrderPrice
    {
        return $this->price;
    }

    public function details(): OrderDetails
    {
        return $this->details;
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

    public function expiresAt(): OrderExpiresAt
    {
        return $this->expiresAt;
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

    public function changeExpiresAt(OrderExpiresAt $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }

    public function changeStatus(OrderStatus $status): void
    {
        $this->status = $status;
    }

    public function changeSubTotal(OrderSubTotal $subTotal): void
    {
        $this->subTotal = $subTotal;
    }

    public function changePrice(OrderPrice $price): void
    {
        $this->price = $price;
    }

    public function changeDetails(OrderDetails $details): void
    {
        $this->details = $details;
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
