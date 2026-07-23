<?php

namespace App\Identity\User\Application\Create;

use App\Identity\User\Domain\Services\NewUserValidator;
use App\Identity\User\Domain\User;
use App\Identity\User\Domain\UserBirthDate;
use App\Identity\User\Domain\UserCity;
use App\Identity\User\Domain\UserCountry;
use App\Identity\User\Domain\UserDocument;
use App\Identity\User\Domain\UserEmail;
use App\Identity\User\Domain\UserLastName;
use App\Identity\User\Domain\UserMobile;
use App\Identity\User\Domain\UserName;
use App\Identity\User\Domain\UserPassword;
use App\Identity\User\Domain\UserRepository;

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