<?php

namespace App\Identity\User\Application\Update;

use App\Identity\User\Domain\Services\UserFinder;
use App\Identity\User\Domain\UserBirthDate;
use App\Identity\User\Domain\UserCity;
use App\Identity\User\Domain\UserCountry;
use App\Identity\User\Domain\UserDocument;
use App\Identity\User\Domain\UserId;
use App\Identity\User\Domain\UserLastName;
use App\Identity\User\Domain\UserMobile;
use App\Identity\User\Domain\UserName;
use App\Identity\User\Domain\UserRepository;

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