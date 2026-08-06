<?php

namespace App\Shared\Domain;

use DateTimeInterface;

trait Audit
{
    private ?AuditCreatedAt $createdAt = null;
    private ?AuditUpdatedAt $updatedAt = null;

    public function createdAt(): AuditCreatedAt
    {
        return $this->createdAt ?? AuditCreatedAt::fromNull();
    }

    public function updatedAt(): AuditUpdatedAt
    {
        return $this->updatedAt ?? AuditUpdatedAt::fromNull();
    }

    public function assignAudit(DateTimeInterface $createdAt, DateTimeInterface $updatedAt): void
    {
        $this->createdAt = AuditCreatedAt::fromDateTime($createdAt);
        $this->updatedAt = AuditUpdatedAt::fromDateTime($updatedAt);
    }
}
