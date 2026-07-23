<?php

namespace App\Shared\Infrastructure;

use App\Shared\Domain\Services\PasswordHasher;
use App\Shared\Infrastructure\Persistence\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SymfonyPasswordHasher implements PasswordHasher
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function hash(string $password): string
    {
        return $this->hasher->hashPassword(new User(), $password);
    }
}