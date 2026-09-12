<?php

namespace App\Catalog\Zone\Application\Delete;

use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Services\CompanyZoneFinder;
use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Zone\Domain\ZoneRepository;

class ZoneDeleter
{
    public function __construct(
        private ZoneRepository $repository,
        private CompanyZoneFinder $zoneFinder,
    ) {
    }

    public function __invoke(ZoneId $id, CompanyId $companyId): void
    {
        $zone = $this->zoneFinder->__invoke($id, $companyId);

        $this->repository->delete($zone);
    }
}
