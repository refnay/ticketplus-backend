<?php

namespace App\Tests\Catalog\Zone\Application\OccupancySummaryReport;

use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Application\OccupancySummaryReport\ZoneReporter;
use App\Catalog\Zone\Domain\ZoneRepository;
use PHPUnit\Framework\TestCase;

final class ZoneReporterTest extends TestCase
{
    public function testItReportsCurrentOccupancyAndAvailability(): void
    {
        $repository = $this->createMock(ZoneRepository::class);
        $repository->expects(self::once())->method('occupancySummary')->willReturn([
            'total' => 4150,
            'sold' => 2946,
            'reserved' => 124,
        ]);

        $response = (new ZoneReporter($repository))->__invoke(
            CompanyId::fromString('018f7c54-5f88-7e04-8a90-7af68c932555'),
        );

        self::assertSame([
            'total' => 4150,
            'sold' => 2946,
            'reserved' => 124,
            'available' => 1080,
            'occupancy' => 0.71,
        ], $response->jsonSerialize());
    }

    public function testItReturnsZeroOccupancyWhenThereIsNoCapacity(): void
    {
        $repository = $this->createStub(ZoneRepository::class);
        $repository->method('occupancySummary')->willReturn([
            'total' => 0,
            'sold' => 0,
            'reserved' => 0,
        ]);

        $response = (new ZoneReporter($repository))->__invoke(
            CompanyId::fromString('018f7c54-5f88-7e04-8a90-7af68c932555'),
        );

        self::assertSame(0.00, $response->jsonSerialize()['occupancy']);
    }
}
