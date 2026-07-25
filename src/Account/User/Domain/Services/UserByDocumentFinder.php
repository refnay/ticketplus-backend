<?php

namespace App\Account\User\Domain\Services;

use App\Account\User\Domain\Exceptions\UserNotFound;
use App\Account\User\Domain\User;
use App\Account\User\Domain\UserDocument;
use App\Account\User\Domain\UserRepository;

class UserByDocumentFinder
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function __invoke(UserDocument $document): User
    {
        $user = $this->repository->findByDocument($document);

        if (is_null($user)) {
            throw new UserNotFound();
        }

        return $user;
    }
}