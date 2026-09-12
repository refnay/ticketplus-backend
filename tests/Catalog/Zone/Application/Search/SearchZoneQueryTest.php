<?php

namespace App\Tests\Catalog\Zone\Application\Search;

use App\Catalog\Zone\Application\Search\SearchZoneQuery;
use PHPUnit\Framework\TestCase;

final class SearchZoneQueryTest extends TestCase
{
    public function testItScopesTheSearchByCompanyAndAcceptsAnOptionalDayFilter(): void
    {
        $query = SearchZoneQuery::fromQuery([
            'day' => '018f7c54-5f88-7e04-8a90-7af68c932551',
            'name' => 'VIP',
        ]);

        self::assertSame([
            'day' => '018f7c54-5f88-7e04-8a90-7af68c932551',
            'name' => 'VIP',
            'company' => '018f7c54-5f88-7e04-8a90-7af68c932555',
        ], $query->filters('018f7c54-5f88-7e04-8a90-7af68c932555'));
    }
}
