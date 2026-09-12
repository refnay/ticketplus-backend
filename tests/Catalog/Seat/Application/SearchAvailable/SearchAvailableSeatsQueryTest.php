<?php

namespace App\Tests\Catalog\Seat\Application\SearchAvailable;

use App\Catalog\Seat\Application\SearchAvailable\SearchAvailableSeatsQuery;
use App\Catalog\Seat\Domain\SeatStatusList;
use PHPUnit\Framework\TestCase;

final class SearchAvailableSeatsQueryTest extends TestCase
{
    public function testItOnlyRequestsAvailableSeatsForTheSelectedZone(): void
    {
        $query = SearchAvailableSeatsQuery::fromQuery('zone-id', [
            'code' => 'A-',
        ]);

        self::assertSame([
            'zone' => 'zone-id',
            'code' => 'A-',
            'status' => SeatStatusList::AVAILABLE->value,
        ], $query->filters());
    }
}
