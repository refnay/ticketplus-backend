<?php

namespace App\Tests\Catalog\Event\Application\BrowsePublished;

use App\Catalog\Event\Application\BrowsePublished\BrowsePublishedEventsQuery;
use App\Catalog\Event\Domain\EventStatusList;
use PHPUnit\Framework\TestCase;

final class BrowsePublishedEventsQueryTest extends TestCase
{
    public function testItAlwaysRestrictsTheCatalogToPublishedEvents(): void
    {
        $query = BrowsePublishedEventsQuery::fromQuery([
            'value' => 'festival',
            'status' => EventStatusList::DRAFT->value,
        ]);

        self::assertSame([
            'value' => 'festival',
            'country' => null,
            'city' => null,
            'category' => null,
            'date' => null,
            'status' => EventStatusList::PUBLISHED->value,
        ], $query->filters());
        self::assertSame('createdAt', $query->orderBy());
        self::assertSame('DESC', $query->order());
        self::assertSame(10, $query->limit());
    }

    public function testItNormalizesUnsafePaginationAndOrdering(): void
    {
        $query = BrowsePublishedEventsQuery::fromQuery([
            'orderBy' => 'invalid_column',
            'order' => 'invalid_order',
            'limit' => 9999,
            'page' => -4,
        ]);

        self::assertSame('createdAt', $query->orderBy());
        self::assertSame('DESC', $query->order());
        self::assertSame(100, $query->limit());
        self::assertSame(1, $query->page());
    }
}
