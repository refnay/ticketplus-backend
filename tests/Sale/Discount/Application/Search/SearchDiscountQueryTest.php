<?php

namespace App\Tests\Sale\Discount\Application\Search;

use App\Sale\Discount\Application\Search\SearchDiscountQuery;
use PHPUnit\Framework\TestCase;

final class SearchDiscountQueryTest extends TestCase
{
    public function testItBuildsFiltersIncludingTheEvent(): void
    {
        $event = '018f7c54-5f88-7e04-8a90-7af68c932551';

        $query = SearchDiscountQuery::fromQuery([
            'event' => $event,
            'code' => 'SAVE5',
            'type' => 1,
            'active' => true,
            'orderBy' => 'createdAt',
            'order' => 'DESC',
        ]);

        self::assertSame([
            'event' => $event,
            'company' => '018f7c54-5f88-7e04-8a90-7af68c932555',
            'code' => 'SAVE5',
            'type' => 1,
            'active' => true,
        ], $query->filters('018f7c54-5f88-7e04-8a90-7af68c932555'));
    }

    public function testTheEventFilterIsOptional(): void
    {
        $query = SearchDiscountQuery::fromQuery([
            'orderBy' => 'createdAt',
            'order' => 'DESC',
        ]);

        self::assertSame([
            'event' => null,
            'company' => '018f7c54-5f88-7e04-8a90-7af68c932555',
            'code' => null,
            'type' => null,
            'active' => null,
        ], $query->filters('018f7c54-5f88-7e04-8a90-7af68c932555'));
    }
}
