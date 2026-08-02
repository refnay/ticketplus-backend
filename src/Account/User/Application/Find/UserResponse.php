<?php

namespace App\Account\User\Application\Find;

use App\Account\User\Domain\User;
use JsonSerializable;
use Override;

final class UserResponse implements JsonSerializable
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

    public static function create(User $user): self
    {
        return new self(
            $user->id()->value(),
            $user->email()->value(),
            $user->name()->value(),
            $user->lastName()->value(),
            $user->birthDate()->asDMY(),
            $user->city()->value(),
            $user->country()->value(),
            $user->document()->toArray(),
            $user->mobile()?->value(),
            $user->profileImage()?->value(),
        );
    }
    
    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    } 
}