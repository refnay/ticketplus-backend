<?php

namespace App\Account\Company\Application\Create;

use App\Account\Company\Domain\CompanyCity;
use App\Account\Company\Domain\CompanyCountry;
use App\Account\Company\Domain\CompanyDescription;
use App\Account\Company\Domain\CompanyDocument;
use App\Account\Company\Domain\CompanyEmail;
use App\Account\Company\Domain\CompanyLocation;
use App\Account\Company\Domain\CompanyName;
use App\Account\Company\Domain\CompanyTelephone;
use App\Account\Company\Domain\CompanyWebSite;
use App\Account\User\Domain\UserId;

class CreateCompanyCommandHandler
{
    public function __construct(private CompanyCreator $creator)
    {
    }

    public function __invoke(CreateCompanyCommand $command): string
    {
        return $this->creator->__invoke(
            CompanyCountry::fromString($command->country()),
            CompanyCity::fromString($command->city()),
            CompanyDocument::create($command->documentType(), $command->documentNumber()),
            CompanyEmail::fromString($command->email()),
            CompanyName::fromString($command->name()),
            CompanyLocation::fromString($command->location()),
            CompanyDescription::fromString($command->description()),
            CompanyTelephone::fromString($command->telephone()),
            CompanyWebSite::fromString($command->webSite()),
            UserId::fromString($command->session()->user()),
        );
    }
}