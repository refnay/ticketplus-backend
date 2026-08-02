<?php

namespace App\Account\User\Application\Company\List;

use JsonSerializable;
use Override;

class UserCompaniesResponse implements JsonSerializable 
{
    private array $companies = [];

    public function __construct(private int $total, UserCompanyResponse ...$companies)
    {
        $this->companies = $companies;
    }

    public function companies(): array
    {
        return $this->companies;
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