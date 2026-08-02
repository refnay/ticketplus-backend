<?php

namespace App\Account\User\Application\ListCompanies;

use App\Account\Company\Domain\CompanyMember;
use App\Account\Company\Domain\CompanyMemberRepository;
use App\Account\Company\Domain\Services\CompanyFinder;

class UserCompaniesLister
{
    public function __construct(private CompanyMemberRepository $repository, private CompanyFinder $companyFinder)
    {
    }

    public function __invoke(
        array $filters,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $offset,
    ): UserCompaniesResponse {
        $companies = $this->repository->searchByFilters($filters, $orderBy, $order, $limit, $offset);
        $total = $this->repository->countByFilters($filters);

        return new UserCompaniesResponse($total, ...array_map($this->makeResponse(), $companies));
    }

    private function makeResponse(): callable
    {
        return function (CompanyMember $companyMember): UserCompanyResponse {
            $company = $this->companyFinder->__invoke($companyMember->companyId());
            return new UserCompanyResponse(
                $companyMember->id()->value(),
                $companyMember->companyId()->value(),
                $company->name()->value(),
                $companyMember->role()->value(),
                $companyMember->status()->value(),
            );
        };
    }
}