<?php

namespace App\Account\User\Infrastructure\Password;

use App\Account\User\Application\Port\Password\PasswordHasher;
use App\Shared\Infrastructure\Persistence\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class SymfonyPasswordHasher implements PasswordHasher
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function hash(string $password): string
    {
        return $this->hasher->hashPassword(new User(), $password);
    }
}
