<?php

namespace App\Account\User\Application\Update;

use App\Account\User\Domain\UserBirthDate;
use App\Account\User\Domain\UserCity;
use App\Account\User\Domain\UserCountry;
use App\Account\User\Domain\UserDocument;
use App\Account\User\Domain\UserId;
use App\Account\User\Domain\UserLastName;
use App\Account\User\Domain\UserMobile;
use App\Account\User\Domain\UserName;

class UpdateUserCommandHandler
{
    public function __construct(private UserUpdater $updater)
    {
    }

    public function __invoke(UpdateUserCommand $command): void
    {
        $this->updater->__invoke(
            UserId::fromString($command->session()->user()),
            UserName::fromString($command->name()),
            UserLastName::fromString($command->lastName()),
            UserBirthDate::fromString($command->birthDate()),
            UserCity::fromString($command->city()),
            UserCountry::fromString($command->country()),
            UserMobile::fromString($command->mobile()),
            UserDocument::create($command->documentType(), $command->documentNumber()),
        );
    }
}