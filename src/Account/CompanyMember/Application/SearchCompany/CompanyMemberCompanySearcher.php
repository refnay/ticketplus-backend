<?php

namespace App\Account\CompanyMember\Application\Company\SearchCompany;

use App\Account\CompanyMember\Domain\CompanyMember;
use App\Account\CompanyMember\Domain\CompanyMemberRepository;
use App\Account\Company\Domain\Services\CompanyFinder;

class CompanyMemberCompanySearcher
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
    ): CompanyMemberCompaniesResponse {
        $companies = $this->repository->searchByFilters($filters, $orderBy, $order, $limit, $offset);
        $total = $this->repository->countByFilters($filters);

        return new CompanyMemberCompaniesResponse($total, ...array_map($this->makeResponse(), $companies));
    }

    private function makeResponse(): callable
    {
        return function (CompanyMember $companyMember): CompanyMemberCompanyResponse {
            $company = $this->companyFinder->__invoke($companyMember->companyId());
            return new CompanyMemberCompanyResponse(
                $companyMember->id()->value(),
                $companyMember->companyId()->value(),
                $company->name()->value(),
                $companyMember->role()->value(),
                $companyMember->status()->value(),
            );
        };
    }
}