<?php

namespace App\Shared\Domain\Services;

use App\Shared\Domain\UserId;

interface PasswordResetter
{
    public function generateToken(UserId $id): string;

    public function validateToken(string $token): string;

    public function removeToken(string $token): void;
}