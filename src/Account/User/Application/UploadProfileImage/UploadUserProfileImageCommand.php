<?php

namespace App\Account\User\Application\UploadProfileImage;

use App\Shared\Application\Input\FileUpload;

class UploadUserProfileImageCommand
{
    public function __construct(private ?FileUpload $profileImage)
    {
    }

    public function profileImage(): ?FileUpload
    {
        return $this->profileImage;
    }
}
