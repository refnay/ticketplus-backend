<?php

namespace App\Account\User\Application\Port\Hash;

interface PasswordHasher
{
    public function hash(string $password): string;
}
