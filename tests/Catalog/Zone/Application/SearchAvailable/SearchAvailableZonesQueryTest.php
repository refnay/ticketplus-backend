<?php

namespace App\Tests\Catalog\Zone\Application\SearchAvailable;

use App\Catalog\Zone\Application\SearchAvailable\SearchAvailableZonesQuery;
use PHPUnit\Framework\TestCase;

final class SearchAvailableZonesQueryTest extends TestCase
{
    public function testItRequestsOnlyZonesWithRemainingInventory(): void
    {
        $query = SearchAvailableZonesQuery::fromQuery('day-id', ['name' => 'VIP']);

        self::assertSame([
            'day' => 'day-id',
            'name' => 'VIP',
            'availableOnly' => true,
        ], $query->filters());
    }
}
