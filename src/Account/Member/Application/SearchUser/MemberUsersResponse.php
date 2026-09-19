<?php

namespace App\Account\Member\Application\SearchUser;

use JsonSerializable;
use Override;

class MemberUsersResponse implements JsonSerializable
{
    private array $users = [];

    public function __construct(private int $total, MemberUserResponse ...$users)
    {
        $this->users = $users;
    }

    public function users(): array
    {
        return $this->users;
    }

    public function total(): int
    {
        return $this->total;
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
