<?php

namespace App\Shared\Domain;

interface SessionProvider
{
    public function user(): string;

    public function company(): ?string;

    public function member(): ?string;

    public function userType(): int;

    public function userStatus(): int;
}