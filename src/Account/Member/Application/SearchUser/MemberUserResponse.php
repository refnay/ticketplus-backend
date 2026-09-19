<?php

namespace App\Account\Member\Application\SearchUser;

use JsonSerializable;
use Override;

class MemberUserResponse implements JsonSerializable
{
    public function __construct(
        readonly private string $id,
        readonly private string $userId,
        readonly private string $userEmail,
        readonly private string $userName,
        readonly private string $userLastName,
        readonly private ?string $userProfileImage,
        readonly private int $role,
        readonly private int $status,
    ) {}

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
