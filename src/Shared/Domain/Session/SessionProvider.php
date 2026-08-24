<?php

namespace App\Shared\Domain\Session;

interface SessionProvider
{
    public function user(): string;

    public function company(): ?string;

    public function member(): ?string;

    public function memberStatus(): ?int;

    public function userType(): int;

    public function userStatus(): int;
}
