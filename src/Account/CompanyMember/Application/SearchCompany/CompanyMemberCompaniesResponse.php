<?php

namespace App\Account\CompanyMember\Application\Company\SearchCompany;

use JsonSerializable;
use Override;

class CompanyMemberCompaniesResponse implements JsonSerializable 
{
    private array $companies = [];

    public function __construct(private int $total, CompanyMemberCompanyResponse ...$companies)
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