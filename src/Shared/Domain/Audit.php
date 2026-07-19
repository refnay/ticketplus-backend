<?php

namespace App\Shared\Domain;

final class Audit
{
    public function __construct(private string $createdAt, private string $updatedAt)
    {
    }

    public function createdAt(): string
    {
        return $this->createdAt;
    }

    public function updatedAt(): string
    {
        return $this->updatedAt;
    }
} 