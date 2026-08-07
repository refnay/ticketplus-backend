<?php

namespace App\Account\User\Application\Company\Search;

use JsonSerializable;
use Override;

class UserCompanyResponse implements JsonSerializable
{
    public function __construct(
        readonly private string $id,
        readonly private string $companyId,
        readonly private string $companyName,
        readonly private int $role,
        readonly private int $status,
    ) {
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
