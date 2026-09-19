<?php

namespace App\Shared\Domain\Utils;

class StringValue
{
    public static function normalize(string $value): string
    {
        return str_replace(['/', '\\'], '-', $value);
    }

    public static function equals(string $a, string $b): bool
    {
        return $a === $b;
    }
}
