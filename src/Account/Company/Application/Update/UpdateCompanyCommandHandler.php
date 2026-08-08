<?php

namespace App\Account\Company\Application\Update;

use App\Account\Company\Domain\CompanyCity;
use App\Account\Company\Domain\CompanyCountry;
use App\Account\Company\Domain\CompanyDescription;
use App\Account\Company\Domain\CompanyDocument;
use App\Account\Company\Domain\CompanyEmail;
use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\CompanyLocation;
use App\Account\Company\Domain\CompanyName;
use App\Account\Company\Domain\CompanyTelephone;
use App\Account\Company\Domain\CompanyWebSite;

class UpdateCompanyCommandHandler
{
    public function __construct(private CompanyUpdater $updater)
    {
    }

    public function __invoke(UpdateCompanyCommand $command): void
    {
        $this->updater->__invoke(
            CompanyId::fromString($command->session()->company()),
            CompanyCountry::fromString($command->country()),
            CompanyCity::fromString($command->city()),
            CompanyDocument::create($command->documentType(), $command->documentNumber()),
            CompanyEmail::fromString($command->email()),
            CompanyName::fromString($command->name()),
            CompanyLocation::fromString($command->location()),
            CompanyDescription::fromString($command->description()),
            CompanyTelephone::fromString($command->telephone()),
            CompanyWebSite::fromString($command->webSite()),
        );
    }
}