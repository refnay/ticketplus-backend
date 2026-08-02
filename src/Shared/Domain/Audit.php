<?php

namespace App\Shared\Domain;

use DateTimeImmutable;

trait Audit
{
    private ?DateTimeImmutable $createdAt = null;
    private ?DateTimeImmutable $updatedAt = null;

    public function createdAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function initialize(object $original): void
    {
        if (method_exists($original, 'getCreatedAt')) {
            $this->createdAt = $original->getCreatedAt();
        }

        if (method_exists($original, 'getUpdatedAt')) {
            $this->updatedAt = $original->getUpdatedAt();
        }
    }
} 