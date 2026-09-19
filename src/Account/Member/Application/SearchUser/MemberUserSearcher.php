<?php

namespace App\Account\Member\Application\SearchUser;

use App\Account\Member\Domain\Member;
use App\Account\Member\Domain\MemberRepository;
use App\Account\User\Domain\Services\UserFinder;

class MemberUserSearcher
{
    public function __construct(private MemberRepository $repository, private UserFinder $userFinder) {}

    public function __invoke(
        array $filters,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $offset,
    ): MemberUsersResponse {
        $users = $this->repository->searchByFilters($filters, $orderBy, $order, $limit, $offset);
        $total = $this->repository->countByFilters($filters);

        return new MemberUsersResponse($total, ...array_map($this->makeResponse(), $users));
    }

    private function makeResponse(): callable
    {
        return function (Member $member): MemberUserResponse {
            $user = $this->userFinder->__invoke($member->userId());

            return new MemberUserResponse(
                $member->id()->value(),
                $user->id()->value(),
                $user->email()->value(),
                $user->name()->value(),
                $user->lastName()->value(),
                $user->profileImage()->value(),
                $member->role()->value(),
                $member->status()->value(),
            );
        };
    }
}
