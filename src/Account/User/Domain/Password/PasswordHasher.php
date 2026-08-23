<?php

namespace App\Account\User\Domain\Password;

interface PasswordHasher
{
    public function hash(string $password): string;
}
