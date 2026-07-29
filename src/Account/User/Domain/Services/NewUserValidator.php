<?php

namespace App\Account\User\Domain\Services;

use App\Account\User\Domain\Exceptions\UserDocumentAlreadyExists;
use App\Account\User\Domain\Exceptions\UserEmailAlreadyExists;
use App\Account\User\Domain\Exceptions\UserNotFound;
use App\Account\User\Domain\UserDocument;
use App\Account\User\Domain\UserEmail;

class NewUserValidator
{
    public function __construct(private UserByEmailFinder $byEmailFinder, private UserByDocumentFinder $byDocumentFinder)
    {
    }

    public function __invoke(UserEmail $email, UserDocument $document): void
    {
        $this->email($email);
        $this->document($document);
    }
    
    public function email(UserEmail $email): void
    {
        try {
            $this->byEmailFinder->__invoke($email);
            throw new UserEmailAlreadyExists();
        } catch (UserNotFound) {}
    }

    public function document(UserDocument $document): void
    {
        try {
            $this->byDocumentFinder->__invoke($document);
            throw new UserDocumentAlreadyExists();
        } catch (UserNotFound) {}
    }
}