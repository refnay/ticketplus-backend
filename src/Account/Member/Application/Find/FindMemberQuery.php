<?php

namespace App\Account\Member\Application\Find;


class FindMemberQuery
{
    public function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }
}