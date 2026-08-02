<?php

namespace App\Account\Company\Domain\Services;

use App\Account\Company\Domain\Company;
use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\CompanyRepository;
use App\Account\Company\Domain\Exceptions\CompanyNotFound;

class CompanyFinder
{
    public function __construct(private CompanyRepository $repository)
    {
    }

    public function __invoke(CompanyId $id): Company
    {
        $company = $this->repository->findById($id);

        if (is_null($company)) {
            throw new CompanyNotFound();
        }

        return $company;
    }
}