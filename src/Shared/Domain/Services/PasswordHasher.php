<?php

namespace App\Shared\Domain\Services;

interface PasswordHasher
{
    public function hash(string $password): string;
}