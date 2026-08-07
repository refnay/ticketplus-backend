<?php

namespace App\Account\User\Application\Company\Find;

use App\Shared\Application\Query\BaseQuery;

class FindUserCompanyQuery extends BaseQuery
{
    public function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }
}