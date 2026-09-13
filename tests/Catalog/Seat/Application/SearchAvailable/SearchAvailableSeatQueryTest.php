<?php

namespace App\Tests\Catalog\Seat\Application\SearchAvailable;

use App\Catalog\Seat\Application\SearchAvailable\SearchAvailableSeatQuery;
use App\Catalog\Seat\Domain\SeatStatusList;
use PHPUnit\Framework\TestCase;

final class SearchAvailableSeatQueryTest extends TestCase
{
    public function testItRequestsAllSeatStatusesForTheSelectedZone(): void
    {
        $query = SearchAvailableSeatQuery::fromQuery('zone-id', [
            'code' => 'A-',
            'orderBy' => 'code',
            'order' => 'ASC',
            'status' => SeatStatusList::SOLD->value,
            'limit' => 25,
            'page' => 2,
        ]);

        self::assertSame([
            'zone' => 'zone-id',
            'code' => 'A-',
        ], $query->filters());
        self::assertSame('code', $query->orderBy());
        self::assertSame('ASC', $query->order());
        self::assertSame(25, $query->limit());
        self::assertSame(25, $query->offset());
    }
}
