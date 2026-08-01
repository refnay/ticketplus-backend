<?php

namespace App\Account\Company\Application\Create;

use App\Account\Company\Domain\Company;
use App\Account\Company\Domain\CompanyCity;
use App\Account\Company\Domain\CompanyCountry;
use App\Account\Company\Domain\CompanyDescription;
use App\Account\Company\Domain\CompanyDocument;
use App\Account\Company\Domain\CompanyEmail;
use App\Account\Company\Domain\CompanyLocation;
use App\Account\Company\Domain\CompanyName;
use App\Account\Company\Domain\CompanyRepository;
use App\Account\Company\Domain\CompanyTelephone;
use App\Account\Company\Domain\CompanyWebSite;
use App\Account\CompanyMember\Domain\CompanyMember;
use App\Account\CompanyMember\Domain\CompanyMemberCurrent;
use App\Account\CompanyMember\Domain\CompanyMemberRole;
use App\Account\User\Domain\Exceptions\UserNotOwner;
use App\Account\User\Domain\Services\UserFinder;
use App\Account\User\Domain\UserId;

class CompanyCreator
{
    public function __construct(private CompanyRepository $repository, private UserFinder $userFinder)
    {
    }

    public function __invoke(
        CompanyCountry $country,
        CompanyCity $city,
        CompanyDocument $document,
        CompanyEmail $email,
        CompanyName $name,
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

        $company = Company::create(
            $city,
            $country,
            $document,
            $email,
            $name,
            $location,
            $description,
            $telephone,
            $webSite,
        );

        $member = CompanyMember::create(
            CompanyMemberRole::owner(),
            CompanyMemberCurrent::enable(),
            $user,
            $company
        );
        $company->addMember($member);

        $this->repository->save($company);

        return $company->id()->value();
    }
}
