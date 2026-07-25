<?php

namespace App\Account\User\Application\Update;

use App\Account\User\Domain\Services\UserFinder;
use App\Account\User\Domain\UserBirthDate;
use App\Account\User\Domain\UserCity;
use App\Account\User\Domain\UserCountry;
use App\Account\User\Domain\UserDocument;
use App\Account\User\Domain\UserId;
use App\Account\User\Domain\UserLastName;
use App\Account\User\Domain\UserMobile;
use App\Account\User\Domain\UserName;
use App\Account\User\Domain\UserRepository;

class UserUpdater
{
    public function __construct(private UserRepository $repository, private UserFinder $finder)
    {
    }

    public function __invoke(
        UserId $id,
        UserName $name,
        UserLastName $lastName,
        UserBirthDate $birthDate,
        UserCity $city,
        UserCountry $country,
        UserMobile $mobile,
        UserDocument $document,
    ): void {
        $user = $this->finder->__invoke($id);

        $user->changeName($name);
        $user->changeLastName($lastName);
        $user->changeBirthDate($birthDate);
        $user->changeCity($city);
        $user->changeCountry($country);
        $user->changeMobile($mobile);
        $user->changeDocument($document);

        $this->repository->update($user);
    }
}