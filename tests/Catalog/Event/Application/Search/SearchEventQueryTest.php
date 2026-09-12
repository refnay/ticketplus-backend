<?php

namespace App\Tests\Catalog\Event\Application\Search;

use App\Catalog\Event\Application\Search\SearchEventQuery;
use PHPUnit\Framework\TestCase;

final class SearchEventQueryTest extends TestCase
{
    public function testItBuildsAFilterStartingFromTheNextDay(): void
    {
        $query = SearchEventQuery::fromQuery([
            'date' => '2025-04-29',
            'category' => '018f7c54-5f88-7e04-8a90-7af68c932551',
            'orderBy' => 'createdAt',
            'order' => 'DESC',
        ]);

        self::assertSame([
            'value' => null,
            'country' => null,
            'city' => null,
            'category' => '018f7c54-5f88-7e04-8a90-7af68c932551',
            'date' => '2025-04-29',
            'status' => null,
            'company' => '018f7c54-5f88-7e04-8a90-7af68c932555',
        ], $query->filters('018f7c54-5f88-7e04-8a90-7af68c932555'));
    }
}
