<?php

namespace App\Tests\Catalog\Seat\Application\Search;

use App\Catalog\Seat\Application\Search\SearchSeatQuery;
use PHPUnit\Framework\TestCase;

final class SearchSeatQueryTest extends TestCase
{
    public function testItScopesTheSearchByCompanyAndAcceptsAnOptionalZoneFilter(): void
    {
        $query = SearchSeatQuery::fromQuery([
            'zone' => '018f7c54-5f88-7e04-8a90-7af68c932551',
            'code' => 'A-',
            'numberedSeating' => 'true',
        ]);

        self::assertSame([
            'zone' => '018f7c54-5f88-7e04-8a90-7af68c932551',
            'code' => 'A-',
            'numberedSeating' => true,
            'company' => '018f7c54-5f88-7e04-8a90-7af68c932555',
        ], $query->filters('018f7c54-5f88-7e04-8a90-7af68c932555'));
    }
}
