<?php

namespace App\Sale\Shared\Domain;

interface EventRepository
{
    public function findById(EventId $id, CompanyId $companyId): ?Event;
}
