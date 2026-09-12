<?php

namespace App\Account\Company\Application\Update;

use App\Shared\Application\Security\AuthorizationContext;
use App\Account\Company\Domain\CompanyCity;
use App\Account\Company\Domain\CompanyCountry;
use App\Account\Company\Domain\CompanyDescription;
use App\Account\Company\Domain\CompanyDefault;
use App\Account\Company\Domain\CompanyDocument;
use App\Account\Company\Domain\CompanyEmail;
use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\CompanyLocation;
use App\Account\Company\Domain\CompanyName;
use App\Account\Company\Domain\CompanyTelephone;
use App\Account\Company\Domain\CompanyTimezone;
use App\Account\Company\Domain\CompanyWebSite;

class UpdateCompanyCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private CompanyUpdater $updater)
    {
    }

    public function __invoke(UpdateCompanyCommand $command): void
    {
        $companyId = $this->authorization->ownerCompanyId();

        $this->updater->__invoke(
            CompanyId::fromString($companyId),
            CompanyCountry::fromString($command->country()),
            CompanyCity::fromString($command->city()),
            CompanyDocument::create($command->documentType(), $command->documentNumber()),
            CompanyEmail::fromString($command->email()),
            CompanyName::fromString($command->name()),
            CompanyTimezone::fromString($command->timezone()),
            CompanyDefault::fromArray($command->default()),
            CompanyLocation::fromString($command->location()),
            CompanyDescription::fromString($command->description()),
            CompanyTelephone::fromString($command->telephone()),
            CompanyWebSite::fromString($command->webSite()),
        );
    }
}
