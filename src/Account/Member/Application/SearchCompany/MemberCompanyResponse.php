<?php

namespace App\Account\Member\Application\SearchCompany;

use JsonSerializable;
use Override;

class MemberCompanyResponse implements JsonSerializable
{
    public function __construct(
        readonly private string $id,
        readonly private string $companyId,
        readonly private string $companyName,
        readonly private ?string $companyLogo,
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
