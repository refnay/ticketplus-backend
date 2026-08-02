<?php

namespace App\Shared\Domain;

use App\Shared\Domain\ValueObjects\DateValueObject;

trait Audit
{
    private DateValueObject $createdAt;
    private DateValueObject $updatedAt;

    public function createdAt(): DateValueObject
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateValueObject
    {
        return $this->updatedAt;
    }

    public function initialize(object $original): void
    {
        if (method_exists($original, 'getCreatedAt')) {
            $this->createdAt = DateValueObject::fromDate($original->getCreatedAt());
        }

        if (method_exists($original, 'getUpdatedAt')) {
            $this->updatedAt = DateValueObject::fromDate($original->getUpdatedAt());
        }
    }
} 