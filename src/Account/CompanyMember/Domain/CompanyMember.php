<?php

namespace App\Account\CompanyMember\Domain;

use App\Account\Company\Domain\Company;
use App\Account\User\Domain\User;

class CompanyMember
{
    private CompanyMemberId $id;
    private CompanyMemberRole $role;
    private CompanyMemberCurrent $current;
    private User $user;
    private Company $company;

    public function __construct(
        CompanyMemberId $id, 
        CompanyMemberRole $role,
        CompanyMemberCurrent $current,
        User $user,
        Company $company
    ) {
        $this->id = $id;
        $this->role = $role;
        $this->current = $current;
        $this->user = $user;
        $this->company = $company;
    }

    public static function create(CompanyMemberCurrent $current, User $user, Company $company): self
    {
        return new self(CompanyMemberId::generate(), CompanyMemberRole::owner(), $current, $user, $company);
    }

    public function id(): CompanyMemberId
    {
        return $this->id;
    }

    public function role(): CompanyMemberRole
    {
        return $this->role;
    }

    public function user(): User
    {
        return $this->user;
    }
    
    public function company(): Company
    {
        return $this->company;
    }

    public function current(): CompanyMemberCurrent
    {
        return $this->current;
    }

    public function changeCurrent(CompanyMemberCurrent $current): void
    {
        $this->current = $current;
    }
}
