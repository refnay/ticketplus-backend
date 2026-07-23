<?php

namespace App\Identity\User\Application\Update;

use App\Identity\User\Domain\UserBirthDate;
use App\Identity\User\Domain\UserCity;
use App\Identity\User\Domain\UserCountry;
use App\Identity\User\Domain\UserDocument;
use App\Identity\User\Domain\UserId;
use App\Identity\User\Domain\UserLastName;
use App\Identity\User\Domain\UserMobile;
use App\Identity\User\Domain\UserName;

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