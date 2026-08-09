<?php

namespace App\Sale\Purchase\Domain;

use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Shared\Domain\UserId;

class Purchase
{
    private PurchaseId $id;
    private PurchaseCurrency $currency;
    private PurchasePaymentMethod $paymentMethod;
    private PurchaseStatus $status;
    private PurchaseSubTotal $subTotal;
    private PurchaseTax $tax;
    private PurchaseTotal $total;
    private UserId $userId;
    private ?DiscountId $discountId = null;

    public function __construct(
        PurchaseId $id,
        PurchaseCurrency $currency,
        PurchasePaymentMethod $paymentMethod,
        PurchaseStatus $status,
        PurchaseSubTotal $subTotal,
        PurchaseTax $tax,
        PurchaseTotal $total,
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
        PurchaseCurrency $currency,
        PurchasePaymentMethod $paymentMethod,
        PurchaseStatus $status,
        PurchaseSubTotal $subTotal,
        PurchaseTax $tax,
        PurchaseTotal $total,
        UserId $userId,
    ): self {
        return new self(
            PurchaseId::generate(),
            $currency,
            $paymentMethod,
            $status,
            $subTotal,
            $tax,
            $total,
            $userId,
        );
    }

    public function id(): PurchaseId
    {
        return $this->id;
    }

    public function currency(): PurchaseCurrency
    {
        return $this->currency;
    }

    public function paymentMethod(): PurchasePaymentMethod
    {
        return $this->paymentMethod;
    }

    public function status(): PurchaseStatus
    {
        return $this->status;
    }

    public function subTotal(): PurchaseSubTotal
    {
        return $this->subTotal;
    }

    public function tax(): PurchaseTax
    {
        return $this->tax;
    }

    public function total(): PurchaseTotal
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

    public function changeCurrency(PurchaseCurrency $currency): void
    {
        $this->currency = $currency;
    }

    public function changePaymentMethod(PurchasePaymentMethod $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function changeStatus(PurchaseStatus $status): void
    {
        $this->status = $status;
    }

    public function changeSubTotal(PurchaseSubTotal $subTotal): void
    {
        $this->subTotal = $subTotal;
    }

    public function changeTax(PurchaseTax $tax): void
    {
        $this->tax = $tax;
    }

    public function changeTotal(PurchaseTotal $total): void
    {
        $this->total = $total;
    }

    public function changeDiscountId(?DiscountId $discountId): void
    {
        $this->discountId = $discountId;
    }
}
