<?php

namespace App\Shared\Infrastructure;

use App\Shared\Domain\Services\PaymentGateway;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class CulqiPaymentGateway implements PaymentGateway
{
    public function __construct(private HttpClientInterface $client, private string $secretKey)
    {
    }

    public function charge(float $amount, string $currency, string $token): string
    {
        return '';
    }
}