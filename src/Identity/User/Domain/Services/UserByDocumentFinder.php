<?php

namespace App\Identity\User\Domain\Services;

use App\Identity\User\Domain\Exceptions\UserNotFound;
use App\Identity\User\Domain\User;
use App\Identity\User\Domain\UserDocument;
use App\Identity\User\Domain\UserRepository;

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