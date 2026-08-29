<?php

namespace App\Account\Company\Application\Create;

use App\Account\Company\Domain\Company;
use App\Account\Company\Domain\CompanyCity;
use App\Account\Company\Domain\CompanyCountry;
use App\Account\Company\Domain\CompanyDescription;
use App\Account\Company\Domain\CompanyDefault;
use App\Account\Company\Domain\CompanyDocument;
use App\Account\Company\Domain\CompanyEmail;
use App\Account\Company\Domain\CompanyLocation;
use App\Account\Company\Domain\CompanyName;
use App\Account\Company\Domain\CompanyRepository;
use App\Account\Company\Domain\CompanyTelephone;
use App\Account\Company\Domain\CompanyTimezone;
use App\Account\Company\Domain\CompanyWebSite;
use App\Account\Company\Domain\Events\CompanyCreatedDomainEvent;
use App\Account\Company\Domain\Exceptions\CompanyDocumentAlreadyExists;
use App\Account\Company\Domain\Exceptions\CompanyNotFound;
use App\Account\Company\Domain\Services\CompanyByDocumentFinder;
use App\Account\User\Domain\Exceptions\UserNotOwner;
use App\Account\User\Domain\Services\UserFinder;
use App\Account\User\Domain\UserId;
use App\Shared\Application\Bus\EventBus;

class CompanyCreator
{
    public function __construct(
        private CompanyRepository $repository,
        private UserFinder $userFinder, 
        private EventBus $eventBus,
        private CompanyByDocumentFinder $companyFinder,
    ) {}

    public function __invoke(
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
        UserId $userId,
    ): string {
        $user = $this->userFinder->__invoke($userId);
        
        if ($user->owner()->isDisable()) {
            throw new UserNotOwner();
        }
        
        try {
            $this->companyFinder->__invoke($document);
            throw new CompanyDocumentAlreadyExists();
        } catch (CompanyNotFound) {
        }
        
        $company = Company::create(
            $city,
            $country,
            $document,
            $email,
            $name,
            $timezone,
            $default,
            $location,
            $description,
            $telephone,
            $webSite,
        );

        $this->repository->save($company);

        $this->eventBus->publish(new CompanyCreatedDomainEvent($user->id()->value(), $company->id()->value()));

        return $company->id()->value();
    }
}
