<?php

namespace App\Account\User\Application\Port\Password;

interface PasswordHasher
{
    public function hash(string $password): string;
}
