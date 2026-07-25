<?php

namespace App\Account\User\Application\Create;

use App\Account\User\Domain\Services\NewUserValidator;
use App\Account\User\Domain\User;
use App\Account\User\Domain\UserBirthDate;
use App\Account\User\Domain\UserCity;
use App\Account\User\Domain\UserCountry;
use App\Account\User\Domain\UserDocument;
use App\Account\User\Domain\UserEmail;
use App\Account\User\Domain\UserLastName;
use App\Account\User\Domain\UserMobile;
use App\Account\User\Domain\UserName;
use App\Account\User\Domain\UserPassword;
use App\Account\User\Domain\UserRepository;

class UserCreator
{
    public function __construct(private UserRepository $repository, private NewUserValidator $validator)
    {
    }

    public function __invoke(
        UserEmail $email,
        UserPassword $password,
        UserBirthDate $birthDate,
        UserCity $city,
        UserCountry $country,
        UserDocument $document,
        UserName $name,
        UserLastName $lastName,
        UserMobile $mobile,
    ): string {
        $this->validator->__invoke($email, $document);

        $user = User::create(
            $email,
            $password,
            $birthDate,
            $city,
            $country,
            $document,
            $lastName,
            $mobile,
            $name,
        );
        
        return $this->repository->save($user);
    }
}