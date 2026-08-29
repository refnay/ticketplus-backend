<?php

namespace App\Shared\Infrastructure\Security;

use App\Shared\Application\Security\CurrentActor;
use App\Shared\Infrastructure\Persistence\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

final class SymfonyCurrentActor implements CurrentActor
{
    public function __construct(private Security $security)
    {
    }

    public function userId(): string
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return $user->getId()->toRfc4122();
    }

    public function companyId(): ?string
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return $user->getCurrentCompany();
    }

    public function memberId(): ?string
    {
        if (is_null($this->companyId())) {
            return null;
        }

        /** @var User $user */
        $user = $this->security->getUser();
        foreach ($user->getCompanies() as $key => $company) {
            if ($company->getCompany()->getId()->toRfc4122() === $this->companyId()) {
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
}
