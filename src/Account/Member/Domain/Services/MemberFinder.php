<?php

namespace App\Account\Member\Domain\Services;

use App\Account\Member\Domain\Member;
use App\Account\Member\Domain\MemberId;
use App\Account\Member\Domain\MemberRepository;
use App\Account\Member\Domain\Exceptions\MemberNotFound;

class MemberFinder
{
    public function __construct(private MemberRepository $repository)
    {
    }

    public function __invoke(MemberId $id): Member
    {
        $member = $this->repository->findById($id);

        if (is_null($member)) {
            throw new MemberNotFound();
        }

        return $member;
    }
}