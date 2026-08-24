<?php

namespace App\Account\User\Application\UploadProfileImage;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadUserProfileImageCommand
{
    public function __construct(private ?UploadedFile $profileImage)
    {
    }

    public function profileImage(): ?UploadedFile
    {
        return $this->profileImage;
    }
}