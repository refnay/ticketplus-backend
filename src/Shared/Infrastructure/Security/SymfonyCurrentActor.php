<?php

namespace App\Shared\Infrastructure\Security;

use App\Shared\Application\Security\CurrentActor;
use App\Shared\Infrastructure\Persistence\Entity\CompanyMember;
use App\Shared\Infrastructure\Persistence\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

final class SymfonyCurrentActor implements CurrentActor
{
    public function __construct(private Security $security)
    {
    }

    public function userId(): ?string
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return null;
        }

        return $user->getId()?->toRfc4122();
    }

    public function companyId(): ?string
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return null;
        }

        return $user->getCurrentCompany();
    }

    public function memberId(): ?string
    {
        return $this->currentMembership()?->getId()?->toRfc4122();
    }

    public function memberRole(): ?int
    {
        return $this->currentMembership()?->getRole();
    }

    public function memberStatus(): ?int
    {
        return $this->currentMembership()?->getStatus();
    }

    private function currentMembership(): ?CompanyMember
    {
        $companyId = $this->companyId();
        if (is_null($companyId)) {
            return null;
        }

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return null;
        }

        foreach ($user->getCompanies() as $membership) {
            if ($membership->getCompany()?->getId()?->toRfc4122() === $companyId) {
                return $membership;
            }
        }

        return null;
    }
}
