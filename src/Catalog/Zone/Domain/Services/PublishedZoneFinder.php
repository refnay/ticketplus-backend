<?php

namespace App\Catalog\Zone\Domain\Services;

use App\Catalog\Zone\Domain\Exceptions\ZoneNotFound;
use App\Catalog\Zone\Domain\Zone;
use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Zone\Domain\ZoneRepository;

class PublishedZoneFinder
{
    public function __construct(private ZoneRepository $repository)
    {
    }

    public function __invoke(ZoneId $id): Zone
    {
        $zone = $this->repository->findPublishedById($id);

        if (is_null($zone)) {
            throw new ZoneNotFound();
        }

        return $zone;
    }
}
