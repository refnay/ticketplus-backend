<?php

namespace App\Account\User\Infrastructure\Reset;

use App\Account\User\Application\Port\Reset\PasswordResetter;
use App\Account\User\Domain\UserId;
use App\Shared\Infrastructure\Persistence\Entity\User as UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

final readonly class SymfonyPasswordResetter implements PasswordResetter
{
    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function generateToken(UserId $id): string
    {
        $userEntity = $this->entityManager->getReference(UserEntity::class, $id->value());

        return $this->resetPasswordHelper->generateResetToken($userEntity)->getToken();
    }

    public function validateToken(string $token): string
    {
        return $this->resetPasswordHelper
            ->validateTokenAndFetchUser($token)
            ->getId()
            ->toRfc4122();
    }

    public function removeToken(string $token): void
    {
        $this->resetPasswordHelper->removeResetRequest($token);
    }
}
