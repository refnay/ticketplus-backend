<?php

namespace App\Account\Member\Domain;

use App\Account\Company\Domain\CompanyId;
use App\Account\User\Domain\UserId;

class Member
{
    private MemberId $id;
    private MemberRole $role;
    private MemberStatus $status;
    private UserId $userId;
    private CompanyId $companyId;

    public function __construct(MemberId $id, MemberRole $role, MemberStatus $status, UserId $userId, CompanyId $companyId)
    {
        $this->id = $id;
        $this->role = $role;
        $this->status = $status;
        $this->userId = $userId;
        $this->companyId = $companyId;
    }

    public static function create(MemberRole $role, UserId $userId, CompanyId $companyId): self
    {
        return new self(MemberId::generate(), $role, MemberStatus::active(), $userId, $companyId);
    }

    public function id(): MemberId
    {
        return $this->id;
    }

    public function role(): MemberRole
    {
        return $this->role;
    }

    public function status(): MemberStatus
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

    public function changeRole(MemberRole $role): void 
    {
        $this->role = $role;
    }

    public function changeStatus(MemberStatus $status): void 
    {
        $this->status = $status;
    }
}
