<?php

namespace App\Tests\Sale\Order\Infrastructure\ExchangeRate;

use App\Sale\Order\Infrastructure\ExchangeRate\FrankfurterExchangeRateProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class FrankfurterExchangeRateProviderTest extends TestCase
{
    public function testItReturnsTheExchangeRate(): void
    {
        $client = new MockHttpClient(function (string $method, string $url): MockResponse {
            self::assertSame('GET', $method);
            self::assertSame('https://api.frankfurter.dev/v2/rate/USD/PEN', $url);

            return new MockResponse(json_encode([
                'date' => '2026-08-28',
                'base' => 'USD',
                'quote' => 'PEN',
                'rate' => 3.53,
            ], JSON_THROW_ON_ERROR));
        });

        $provider = new FrankfurterExchangeRateProvider($client);

        self::assertSame(3.53, $provider->rate('usd', 'pen'));
    }

    public function testItRejectsAMissingExchangeRate(): void
    {
        $client = new MockHttpClient(new MockResponse('{}'));
        $provider = new FrankfurterExchangeRateProvider($client);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Frankfurter did not return a valid exchange rate.');

        $provider->rate('USD', 'PEN');
    }

    public function testItReturnsOneWithoutRequestingWhenCurrenciesAreTheSame(): void
    {
        $client = new MockHttpClient(static function (): never {
            self::fail('The provider should not make a request for the same currency.');
        });
        $provider = new FrankfurterExchangeRateProvider($client);

        self::assertSame(1.00, $provider->rate('pen', 'PEN'));
    }

    public function testItRejectsANonPositiveExchangeRate(): void
    {
        $client = new MockHttpClient(new MockResponse('{"rate": 0}'));
        $provider = new FrankfurterExchangeRateProvider($client);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Frankfurter returned a non-positive exchange rate.');

        $provider->rate('USD', 'PEN');
    }
}
