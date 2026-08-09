<?php

namespace App\Account\Member\Application\Find;

use App\Shared\Application\Query\BaseQuery;

class FindMemberQuery extends BaseQuery
{
    public function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }
}