<?php

namespace App\Shared\Domain;

use App\Identity\User\Domain\User;

interface ResetPasswordProvider
{
    public function generateToken(User $user): string;
}