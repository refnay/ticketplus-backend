<?php

namespace App\Account\User\Application\Find;

final class UserResponse
{
    public function __construct(
        private readonly string $id,
        private readonly string $email,
        private readonly string $name,
        private readonly string $lastName,
        private readonly string $birthDate,
        private readonly string $city,
        private readonly string $country,
        private readonly array $document,
        private readonly ?string $mobile,
        private readonly ?string $profileImage,
    ) {
    }
    
    public function toArray(): array
    {
        return get_object_vars($this);
    } 
}