<?php

namespace App\Account\User\Application\Port\Reset;

use App\Account\User\Domain\UserId;

interface PasswordResetter
{
    public function generateToken(UserId $id): string;

    public function validateToken(string $token): string;

    public function removeToken(string $token): void;
}
