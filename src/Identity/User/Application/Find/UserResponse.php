<?php

namespace App\Identity\User\Application\Find;

final class UserResponse
{
    public function __construct(
        private readonly string $id,
        private readonly string $email,
        private readonly string $name,
        private readonly string $lastName,
    ) {
    }
    
    public function toArray(): array
    {
        return get_object_vars($this);
    } 
}