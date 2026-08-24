<?php

namespace App\Shared\Application\Security;

interface CurrentActor
{
    public function userId(): string;

    public function companyId(): ?string;

    public function memberId(): ?string;

    public function memberStatus(): ?int;

    public function userType(): int;

    public function userStatus(): int;
}
