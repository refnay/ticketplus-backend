<?php

namespace App\Identity\User\Application\Find;

use App\Shared\Application\Query\BaseQuery;

class FindUserQuery extends BaseQuery
{
    public function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }
}