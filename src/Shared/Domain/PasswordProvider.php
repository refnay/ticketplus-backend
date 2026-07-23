<?php

namespace App\Shared\Domain;

interface PasswordProvider
{
    public function hash(string $password): string;
}