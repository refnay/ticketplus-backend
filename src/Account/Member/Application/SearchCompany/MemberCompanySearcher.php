<?php

namespace App\Account\Member\Application\SearchCompany;

use App\Account\Member\Domain\Member;
use App\Account\Member\Domain\MemberRepository;
use App\Account\Company\Domain\Services\CompanyFinder;

class MemberCompanySearcher
{
    public function __construct(private MemberRepository $repository, private CompanyFinder $companyFinder)
    {
    }

    public function __invoke(
        array $filters,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $offset,
    ): MemberCompaniesResponse {
        $companies = $this->repository->searchByFilters($filters, $orderBy, $order, $limit, $offset);
        $total = $this->repository->countByFilters($filters);

        return new MemberCompaniesResponse($total, ...array_map($this->makeResponse(), $companies));
    }

    private function makeResponse(): callable
    {
        return function (Member $member): MemberCompanyResponse {
            $company = $this->companyFinder->__invoke($member->companyId());
            return new MemberCompanyResponse(
                $member->id()->value(),
                $company->id()->value(),
                $company->name()->value(),
                $company->logo()->value(),
                $member->role()->value(),
                $member->status()->value(),
            );
        };
    }
}