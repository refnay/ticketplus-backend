<?php

namespace App\Shared\Infrastructure;

use App\Shared\Domain\SessionProvider;
use App\Shared\Infrastructure\Persistence\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

class SymfonySessionProvider implements SessionProvider
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

    public function company(): ?string
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return $user->getCurrentCompany();
    }

    public function member(): ?string
    {
        if (is_null($this->company())) {
            return null;
        }

        /** @var User $user */
        $user = $this->security->getUser();

        foreach ($user->getCompanies() as $company) {
            if ($company->getCompany()->getId()->toRfc4122() === $this->company()) {
                return $company->getId()->toRfc4122();
            } 
        }
        
        return null;
    }

    public function userType(): int
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return $user->getType();
    }

    public function userStatus(): int
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return $user->getStatus();
    }
}