<?php

namespace App\Account\Company\Domain;

use App\Account\Company\Domain\CompanyId;
use App\Account\User\Domain\UserId;

class CompanyMember
{
    private CompanyMemberId $id;
    private CompanyMemberRole $role;
    private CompanyMemberStatus $status;
    private UserId $userId;
    private CompanyId $companyId;

    public function __construct(CompanyMemberId $id, CompanyMemberRole $role, CompanyMemberStatus $status, UserId $userId, CompanyId $companyId)
    {
        $this->id = $id;
        $this->role = $role;
        $this->status = $status;
        $this->userId = $userId;
        $this->companyId = $companyId;
    }

    public static function create(CompanyMemberRole $role, UserId $userId, CompanyId $companyId): self
    {
        return new self(CompanyMemberId::generate(), $role, CompanyMemberStatus::active(), $userId, $companyId);
    }

    public function id(): CompanyMemberId
    {
        return $this->id;
    }

    public function role(): CompanyMemberRole
    {
        return $this->role;
    }

    public function status(): CompanyMemberStatus
    {
        return $this->status;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }
    
    public function companyId(): CompanyId
    {
        return $this->companyId;
    }
}
