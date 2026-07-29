<?php

namespace App\Account\Company\Domain\Services;

use App\Account\Company\Domain\Company;
use App\Account\Company\Domain\CompanyDocument;
use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\CompanyRepository;
use App\Account\Company\Domain\Exceptions\CompanyNotFound;

class CompanyByDocumentFinder
{
    public function __construct(private CompanyRepository $repository)
    {
    }

    public function __invoke(CompanyDocument $document): Company
    {
        $company = $this->repository->findByDocument($document);

        if (is_null($company)) {
            throw new CompanyNotFound();
        }

        return $company;
    }
}