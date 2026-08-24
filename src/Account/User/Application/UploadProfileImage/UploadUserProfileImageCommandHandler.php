<?php

namespace App\Account\User\Application\UploadProfileImage;

use App\Shared\Application\Security\AuthorizationContext;
use App\Account\User\Domain\UserId;

class UploadUserProfileImageCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private UserProfileImageUploader $uploader)
    {
    }

    public function __invoke(UploadUserProfileImageCommand $command): void
    {
        $this->uploader->__invoke(
            UserId::fromString($this->authorization->userId()),
            $command->profileImage(),
        );
    }
}