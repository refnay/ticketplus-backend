<?php

namespace App\Shared\Infrastructure;

use App\Shared\Domain\PasswordProvider;
use App\Shared\Infrastructure\Persistence\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SymfonyPasswordProvider implements PasswordProvider
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function hash(string $password): string
    {
        return $this->hasher->hashPassword(new User(), $password);
    }
}