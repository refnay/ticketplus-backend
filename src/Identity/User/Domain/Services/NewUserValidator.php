<?php

namespace App\Identity\User\Domain\Services;

use App\Identity\User\Domain\Exceptions\UserDocumentAlreadyExists;
use App\Identity\User\Domain\Exceptions\UserEmailAlreadyExists;
use App\Identity\User\Domain\Exceptions\UserNotFound;
use App\Identity\User\Domain\UserDocument;
use App\Identity\User\Domain\UserEmail;

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