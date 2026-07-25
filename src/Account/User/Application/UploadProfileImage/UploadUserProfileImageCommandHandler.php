<?php

namespace App\Account\User\Application\UploadProfileImage;

use App\Account\User\Domain\UserId;

class UploadUserProfileImageCommandHandler
{
    public function __construct(private UserProfileImageUploader $uploader)
    {
    }

    public function __invoke(UploadUserProfileImageCommand $command): void
    {
        $this->uploader->__invoke(
            UserId::fromString($command->session()->user()),
            $command->profileImage(),
        );
    }
}