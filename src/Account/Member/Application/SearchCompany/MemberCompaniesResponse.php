<?php

namespace App\Account\Member\Application\SearchCompany;

use JsonSerializable;
use Override;

class MemberCompaniesResponse implements JsonSerializable 
{
    private array $companies = [];

    public function __construct(private int $total, MemberCompanyResponse ...$companies)
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