<?php

namespace App\Account\CompanyMember\Application\Company\Find;

use App\Shared\Application\Query\BaseQuery;

class FindCompanyMemberQuery extends BaseQuery
{
    public function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }
}