<?php

namespace App\Sale\Event\Domain;

use App\Sale\Purchase\Domain\CompanyId;

interface EventRepository
{
    public function findById(EventId $id, CompanyId $companyId): ?Event;
}