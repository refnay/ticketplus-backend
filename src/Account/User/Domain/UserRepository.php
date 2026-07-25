<?php

namespace App\Account\User\Domain;

interface UserRepository
{
    public function save(User $user): string;

    public function update(User $user): void;

    public function delete(User $user): void;

    public function findById(UserId $id): ?User;

    public function findByEmail(UserEmail $email): ?User;

    public function findByDocument(UserDocument $document): ?User;

    public function updatePassword(UserId $id, UserPassword $oldPassword, UserPassword $newPassword): void;

    public function resetPassword(UserId $id, UserPassword $newPassword): void;
}