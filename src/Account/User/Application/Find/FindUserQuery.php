<?php

namespace App\Account\User\Application\Find;


class FindUserQuery
{
    public function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }
}