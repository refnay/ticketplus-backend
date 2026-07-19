<?php

namespace App\Shared\Infrastructure;

use App\Shared\Domain\SessionProvider;
use App\Shared\Infrastructure\Persistence\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

class SymfonyProvider implements SessionProvider
{
    public function __construct(private Security $security)
    {
    }

    public function user(): string
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return $user->getId()->toRfc4122();
    }
}