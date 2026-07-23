<?php

namespace App\Identity\User\Application\UploadProfileImage;

use App\Identity\User\Domain\Exceptions\UserProfileImageNotUploaded;
use App\Identity\User\Domain\Services\UserFinder;
use App\Identity\User\Domain\UserId;
use App\Identity\User\Domain\UserProfileImage;
use App\Identity\User\Domain\UserRepository;
use App\Shared\Domain\Services\ImageUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;

class UserProfileImageUploader
{
    public function __construct(
        private UserRepository $repository,
        private UserFinder $finder,
        private ImageUploader $uploader
    ) {
    }

    public function __invoke(UserId $id, ?UploadedFile $profileImage): void
    {
        $user = $this->finder->__invoke($id);

        if (!is_null($profileImage)) {
            try {
                $url = $this->uploader->upload($profileImage->getRealPath());
                $user->changeProfileImage(UserProfileImage::fromString($url));
            } catch (Throwable) {
                throw new UserProfileImageNotUploaded();
            }
        } else {
            $user->changeProfileImage(UserProfileImage::fromEmpty());
        }

        $this->repository->update($user);
    }
}