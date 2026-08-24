<?php

namespace App\Sale\Event\Domain;

use App\Sale\Shared\Domain\CompanyId;

interface EventRepository
{
    public function findById(EventId $id, ?CompanyId $companyId = null): ?Event;
}
