<?php

namespace App\Catalog\Zone\Application\Find;

use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Services\CompanyZoneFinder;
use App\Catalog\Zone\Domain\ZoneId;

class ZoneFinder
{
    public function __construct(private CompanyZoneFinder $zoneFinder)
    {
    }

    public function __invoke(ZoneId $id, CompanyId $companyId): ZoneResponse
    {
        $zone = $this->zoneFinder->__invoke($id, $companyId);

        return ZoneResponse::create($zone);
    }
}
