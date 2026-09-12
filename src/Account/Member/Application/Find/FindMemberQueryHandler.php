<?php

namespace App\Account\Member\Application\Find;

use App\Shared\Application\Security\AuthorizationContext;
use App\Account\Member\Domain\MemberId;

class FindMemberQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private MemberFinder $finder)
    {
    }

    public function __invoke(FindMemberQuery $query): MemberResponse
    {
        return $this->finder->__invoke(MemberId::fromString($this->authorization->memberId()));
    }
}
