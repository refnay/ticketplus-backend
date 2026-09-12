<?php

namespace App\Account\Member\Application\Find;

use JsonSerializable;
use Override;

final class MemberResponse implements JsonSerializable
{
    public function __construct(
        private readonly string $id,
        private readonly string $userId,
        private readonly string $userName,
        private readonly string $companyId,
        private readonly string $companyName,
        private readonly int $role,
        private readonly int $status,
    ) {
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}