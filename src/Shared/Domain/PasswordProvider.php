<?php

namespace App\Shared\Domain;

interface PasswordProvider
{
    public function hash(string $password): string;

    public function isValid(string $hashedPassword, string $plainPassword): bool;
}