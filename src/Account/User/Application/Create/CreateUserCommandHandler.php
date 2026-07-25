<?php

namespace App\Account\User\Application\Create;

use App\Account\User\Domain\UserBirthDate;
use App\Account\User\Domain\UserCity;
use App\Account\User\Domain\UserCountry;
use App\Account\User\Domain\UserDocument;
use App\Account\User\Domain\UserEmail;
use App\Account\User\Domain\UserLastName;
use App\Account\User\Domain\UserMobile;
use App\Account\User\Domain\UserName;
use App\Account\User\Domain\UserPassword;
use App\Shared\Domain\Services\PasswordHasher;

class CreateUserCommandHandler
{
    public function __construct(private UserCreator $creator, private PasswordHasher $hasher)
    {
    }

    public function __invoke(CreateUserCommand $command): string
    {
        return $this->creator->__invoke(
            UserEmail::fromString($command->email()),
            UserPassword::fromString($this->hasher->hash($command->password())),
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