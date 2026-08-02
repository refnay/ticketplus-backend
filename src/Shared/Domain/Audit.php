<?php

namespace App\Shared\Domain;

use App\Shared\Domain\ValueObjects\DateTimeValueObject;

trait Audit
{
    private DateTimeValueObject $createdAt;
    private DateTimeValueObject $updatedAt;

    public function createdAt(): DateTimeValueObject
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeValueObject
    {
        return $this->updatedAt;
    }

    public function initialize(object $original): void
    {
        if (method_exists($original, 'getCreatedAt')) {
            $this->createdAt = DateTimeValueObject::fromDateTime($original->getCreatedAt());
        }

        if (method_exists($original, 'getUpdatedAt')) {
            $this->updatedAt = DateTimeValueObject::fromDateTime($original->getUpdatedAt());
        }
    }
} 