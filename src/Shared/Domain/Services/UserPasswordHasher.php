<?php

namespace App\Shared\Domain\Services;

use App\Shared\Infrastructure\Persistence\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPasswordHasher
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function __invoke(string $password): string
    {
        return $this->hasher->hashPassword(new User(), $password);
    }
}