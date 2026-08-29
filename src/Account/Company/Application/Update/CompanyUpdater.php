<?php

namespace App\Account\Company\Application\Update;

use App\Account\Company\Domain\CompanyCity;
use App\Account\Company\Domain\CompanyCountry;
use App\Account\Company\Domain\CompanyDescription;
use App\Account\Company\Domain\CompanyDefault;
use App\Account\Company\Domain\CompanyDocument;
use App\Account\Company\Domain\CompanyEmail;
use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\CompanyLocation;
use App\Account\Company\Domain\CompanyName;
use App\Account\Company\Domain\CompanyRepository;
use App\Account\Company\Domain\CompanyTelephone;
use App\Account\Company\Domain\CompanyTimezone;
use App\Account\Company\Domain\CompanyWebSite;
use App\Account\Company\Domain\Exceptions\CompanyDocumentAlreadyExists;
use App\Account\Company\Domain\Exceptions\CompanyNotFound;
use App\Account\Company\Domain\Services\CompanyByDocumentFinder;
use App\Account\Company\Domain\Services\CompanyFinder;

class CompanyUpdater
{
    public function __construct(
        private CompanyRepository $repository,
        private CompanyFinder $companyFinder,
        private CompanyByDocumentFinder $companyByDocumentFinder,
    ) {}

    public function __invoke(
        CompanyId $id,
        CompanyCountry $country,
        CompanyCity $city,
        CompanyDocument $document,
        CompanyEmail $email,
        CompanyName $name,
        CompanyTimezone $timezone,
        CompanyDefault $default,
        CompanyLocation $location,
        CompanyDescription $description,
        CompanyTelephone $telephone,
        CompanyWebSite $webSite,
    ): void {
        $company = $this->companyFinder->__invoke($id);

        if (!$company->document()->equals($document->type(), $document->number())) {
            try {
                $this->companyByDocumentFinder->__invoke($document);
                throw new CompanyDocumentAlreadyExists();
            } catch (CompanyNotFound) {
                $company->changeDocument($document);
            }
        }

        $company->changeCountry($country);
        $company->changeCity($city);
        $company->changeEmail($email);
        $company->changeName($name);
        $company->changeTimezone($timezone);
        $company->changeDefault($default);
        $company->changeLocation($location);
        $company->changeDescription($description);
        $company->changeTelephone($telephone);
        $company->changeWebSite($webSite);

        $this->repository->update($company);
    }
}
