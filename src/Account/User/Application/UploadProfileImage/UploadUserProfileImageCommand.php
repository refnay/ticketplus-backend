<?php

namespace App\Account\User\Application\UploadProfileImage;

use App\Shared\Application\Command\BaseCommand;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadUserProfileImageCommand extends BaseCommand
{
    public function __construct(private ?UploadedFile $profileImage)
    {
    }

    public function profileImage(): ?UploadedFile
    {
        return $this->profileImage;
    }
}