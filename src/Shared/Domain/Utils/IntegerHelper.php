<?php

namespace App\Shared\Domain\Utils;

class IntegerHelper
{
    public static function isEqual(int $a, int $b): bool
    {
        return $a === $b;
    }
}