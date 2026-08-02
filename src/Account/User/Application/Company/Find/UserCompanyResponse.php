<?php

namespace App\Account\User\Application\Company\Find;

use JsonSerializable;
use Override;

final class UserCompanyResponse implements JsonSerializable
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $lastName,
        private readonly int $role,
        private readonly int $status,
        private readonly string $companyName,
    ) {
    }
    
    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    } 
}