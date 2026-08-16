<?php

namespace App\Shared\Domain\Services;

interface PaymentGateway
{
    public function charge(float $amount, string $currency, string $token): string;
}
