<?php

namespace App\Catalog\Zone\Application\Update;

use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Services\CompanyZoneFinder;
use App\Catalog\Zone\Domain\ZoneHierarchy;
use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Zone\Domain\ZoneName;
use App\Catalog\Zone\Domain\ZoneNumberedSeating;
use App\Catalog\Zone\Domain\ZonePrice;
use App\Catalog\Zone\Domain\ZoneQuantity;
use App\Catalog\Zone\Domain\ZoneRepository;

class ZoneUpdater
{
    public function __construct(
        private ZoneRepository $repository,
        private CompanyZoneFinder $zoneFinder,
    ) {
    }

    public function __invoke(
        ZoneId $id,
        ZoneName $name,
        ZonePrice $price,
        ZoneQuantity $quantity,
        ZoneHierarchy $hierarchy,
        ZoneNumberedSeating $numberedSeating,
        CompanyId $companyId,
    ): void {
        $zone = $this->zoneFinder->__invoke($id, $companyId);

        $zone->changeName($name);
        $zone->changePrice($price);
        $zone->changeTotalQuantity($quantity->total());
        $zone->changeHierarchy($hierarchy);
        $zone->changeNumberedSeating($numberedSeating);

        $this->repository->update($zone);
    }
}
