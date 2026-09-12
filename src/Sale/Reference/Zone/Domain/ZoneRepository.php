<?php

namespace App\Sale\Reference\Zone\Domain;

interface ZoneRepository
{
    public function findById(ZoneId $id): ?Zone;
}
