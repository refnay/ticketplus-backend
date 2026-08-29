<?php

namespace App\Sale\Order\Infrastructure\ExchangeRate;

use App\Sale\Order\Application\Port\ExchangeRate\ExchangeRateProvider;
use RuntimeException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class FrankfurterExchangeRateProvider implements ExchangeRateProvider
{
    private const string API_URL = 'https://api.frankfurter.dev/v2/rate/%s/%s';

    public function __construct(private HttpClientInterface $client)
    {
    }

    public function rate(string $baseCurrency, string $quoteCurrency): float
    {
        $baseCurrency = strtoupper($baseCurrency);
        $quoteCurrency = strtoupper($quoteCurrency);

        if ($baseCurrency === $quoteCurrency) {
            return 1.00;
        }

        $response = $this->client->request(
            'GET',
            sprintf(
                self::API_URL,
                $baseCurrency,
                $quoteCurrency,
            ),
            ['timeout' => 5],
        );
        $data = $response->toArray();
        $rate = $data['rate'] ?? null;

        if (!is_int($rate) && !is_float($rate)) {
            throw new RuntimeException('Frankfurter did not return a valid exchange rate.');
        }

        $rate = (float) $rate;

        if (!is_finite($rate) || $rate <= 0.00) {
            throw new RuntimeException('Frankfurter returned a non-positive exchange rate.');
        }

        return $rate;
    }
}
