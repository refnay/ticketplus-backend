<?php

namespace App\Tests\Catalog\Zone\Domain\Services;

use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Exceptions\ZoneNotFound;
use App\Catalog\Zone\Domain\Services\ZoneByCompanyFinder;
use App\Catalog\Zone\Domain\Zone;
use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Zone\Domain\ZoneRepository;
use PHPUnit\Framework\TestCase;

final class ZoneByCompanyFinderTest extends TestCase
{
    public function testItReturnsTheZoneWithinTheCompany(): void
    {
        $id = ZoneId::fromString('018f7c54-5f88-7e04-8a90-7af68c932551');
        $companyId = CompanyId::fromString('018f7c54-5f88-7e04-8a90-7af68c932555');
        $zone = $this->createStub(Zone::class);
        $repository = $this->createMock(ZoneRepository::class);
        $repository->expects(self::never())->method('find');
        $repository->expects(self::once())->method('findById')
            ->with($id, $companyId)->willReturn($zone);

        self::assertSame($zone, (new ZoneByCompanyFinder($repository))($id, $companyId));
    }

    public function testItRejectsAZoneNotFoundWithinTheCompany(): void
    {
        $id = ZoneId::fromString('018f7c54-5f88-7e04-8a90-7af68c932551');
        $companyId = CompanyId::fromString('018f7c54-5f88-7e04-8a90-7af68c932555');
        $repository = $this->createMock(ZoneRepository::class);
        $repository->expects(self::never())->method('find');
        $repository->expects(self::once())->method('findById')
            ->with($id, $companyId)->willReturn(null);

        $this->expectException(ZoneNotFound::class);

        (new ZoneByCompanyFinder($repository))($id, $companyId);
    }
}
