<?php

namespace App\Sale\Reference\User\Domain;

interface UserRepository
{
    public function findById(UserId $id): ?User;
}
