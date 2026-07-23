<?php

namespace App\Shared\Domain;

use App\Identity\User\Domain\User;

interface ResetPasswordProvider
{
    public function generateToken(UserId $id): string;

    public function validateToken(string $token): void;

    public function removeToken(string $token): void;
}