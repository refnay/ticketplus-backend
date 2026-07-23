<?php

namespace App\Shared\Infrastructure;

use App\Shared\Domain\Services\PasswordResetter;
use App\Shared\Domain\UserId;
use App\Shared\Infrastructure\Persistence\Entity\User as UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class SymfonyPasswordResetter implements PasswordResetter
{
    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper, 
        private EntityManagerInterface $entityManager
    ) {
    }

    public function generateToken(UserId $id): string
    {
        $userEntity = $this->entityManager->getReference(UserEntity::class, $id->value());
        
        return $this->resetPasswordHelper->generateResetToken($userEntity)->getToken();
    }

    public function validateToken(string $token): string
    {
        return $this->resetPasswordHelper->validateTokenAndFetchUser($token)->getId();
    }

    public function removeToken(string $token): void
    {
        $this->resetPasswordHelper->removeResetRequest($token);
    }
}