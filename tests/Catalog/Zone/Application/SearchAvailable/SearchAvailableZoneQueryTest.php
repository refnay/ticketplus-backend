<?php

namespace App\Tests\Catalog\Zone\Application\SearchAvailable;

use App\Catalog\Zone\Application\SearchAvailable\SearchAvailableZoneQuery;
use PHPUnit\Framework\TestCase;

final class SearchAvailableZoneQueryTest extends TestCase
{
    public function testItIncludesZonesWithoutRemainingInventory(): void
    {
        $query = SearchAvailableZoneQuery::fromQuery('day-id', [
            'name' => 'VIP',
            'orderBy' => 'hierarchy',
            'order' => 'ASC',
            'limit' => 20,
            'page' => 3,
            'availableOnly' => true,
        ]);

        self::assertSame([
            'day' => 'day-id',
            'name' => 'VIP',
        ], $query->filters());
        self::assertSame('hierarchy', $query->orderBy());
        self::assertSame('ASC', $query->order());
        self::assertSame(20, $query->limit());
        self::assertSame(40, $query->offset());
    }
}
