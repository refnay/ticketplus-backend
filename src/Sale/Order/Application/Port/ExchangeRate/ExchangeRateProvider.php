<?php

namespace App\Sale\Order\Application\Port\ExchangeRate;

interface ExchangeRateProvider
{
    public function rate(string $baseCurrency, string $quoteCurrency): float;
}
