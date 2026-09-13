<?php

namespace App\Tests\Catalog\Event\Application\BrowsePublished;

use App\Catalog\Event\Application\BrowsePublished\BrowsePublishedEventQuery;
use App\Catalog\Event\Domain\EventStatusList;
use PHPUnit\Framework\TestCase;

final class BrowsePublishedEventQueryTest extends TestCase
{
    public function testItAlwaysRestrictsTheCatalogToPublishedEvents(): void
    {
        $query = BrowsePublishedEventQuery::fromQuery([
            'value' => 'festival',
            'orderBy' => 'createdAt',
            'order' => 'DESC',
            'limit' => 10,
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

    public function testItMapsOrderingAndPaginationLikeSearchEvent(): void
    {
        $query = BrowsePublishedEventQuery::fromQuery([
            'orderBy' => 'name',
            'order' => 'ASC',
            'limit' => 25,
            'page' => 3,
        ]);

        self::assertSame('name', $query->orderBy());
        self::assertSame('ASC', $query->order());
        self::assertSame(25, $query->limit());
        self::assertSame(3, $query->page());
        self::assertSame(50, $query->offset());
    }
}
