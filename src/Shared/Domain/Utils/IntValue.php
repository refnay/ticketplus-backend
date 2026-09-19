<?php

namespace App\Shared\Domain\Utils;

class IntValue
{
    public static function equals(int $a, int $b): bool
    {
        return $a === $b;
    }
}