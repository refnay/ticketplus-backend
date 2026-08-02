<?php

namespace App\Shared\Domain;

interface SessionProvider
{
    public function user(): string;

    public function company(): ?string;

    public function type(): int;
}