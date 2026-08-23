<?php

namespace App\Sale\Shared\Domain;

interface UserRepository
{
    public function findById(UserId $id): ?User;
}
