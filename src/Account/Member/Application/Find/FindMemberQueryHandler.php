<?php

namespace App\Account\Member\Application\Find;

use App\Account\Member\Domain\MemberId;

class FindMemberQueryHandler
{
    public function __construct(private MemberFinder $finder)
    {
    }

    public function __invoke(FindMemberQuery $query): MemberResponse
    {
        return $this->finder->__invoke(MemberId::fromString($query->session()->member()));
    }
}