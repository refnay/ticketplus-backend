<?php

namespace App\Shared\Infrastructure;

use App\Identity\User\Domain\User;
use App\Shared\Domain\ResetPasswordProvider;
use App\Shared\Infrastructure\Persistence\Entity\User as UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class SymfonyResetPasswordProvider implements ResetPasswordProvider
{
    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper, 
        private EntityManagerInterface $entityManager
    ) {
    }

    public function generateToken(User $user): string
    {
        $userEntity = $this->entityManager->getReference(UserEntity::class, $user->id()->value());
        
        return $this->resetPasswordHelper->generateResetToken($userEntity)->getToken();
    }
}