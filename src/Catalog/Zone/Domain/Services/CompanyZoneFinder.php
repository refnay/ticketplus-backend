<?php

namespace App\Catalog\Zone\Domain\Services;

use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Exceptions\ZoneNotFound;
use App\Catalog\Zone\Domain\Zone;
use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Zone\Domain\ZoneRepository;

class CompanyZoneFinder
{
    public function __construct(private ZoneRepository $repository)
    {
    }

    public function __invoke(ZoneId $id, CompanyId $companyId): Zone
    {
        $zone = $this->repository->findById($id, $companyId);

        if (is_null($zone)) {
            throw new ZoneNotFound();
        }

        return $zone;
    }
}
