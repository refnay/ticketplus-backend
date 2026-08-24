<?php

namespace App\Sale\User\Domain;

interface UserRepository
{
    public function findById(UserId $id): ?User;
}
