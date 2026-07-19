<?php

namespace App\Identity\User\Application\Create;

use App\Identity\User\Domain\UserBirthDate;
use App\Identity\User\Domain\UserCity;
use App\Identity\User\Domain\UserCountry;
use App\Identity\User\Domain\UserDocument;
use App\Identity\User\Domain\UserEmail;
use App\Identity\User\Domain\UserLastName;
use App\Identity\User\Domain\UserMobile;
use App\Identity\User\Domain\UserName;
use App\Identity\User\Domain\UserPassword;
use App\Shared\Domain\Services\UserPasswordHasher;

class CreateUserCommandHandler
{
    public function __construct(private UserCreator $creator, private UserPasswordHasher $hasher)
    {
    }

    public function __invoke(CreateUserCommand $command): string
    {
        return $this->creator->__invoke(
            UserEmail::fromString($command->email()),
            UserPassword::fromString($this->hasher->__invoke($command->password())),
            UserBirthDate::fromString($command->birthDate()),
            UserCity::fromString($command->city()),
            UserCountry::fromString($command->country()),
            UserDocument::create($command->documentType(), $command->documentNumber()),
            UserName::fromString($command->name()),
            UserLastName::fromString($command->lastName()),
            UserMobile::fromString($command->mobile()),
        );
    }
}