<?php

namespace App\Shared\Domain\Utils;

class StringHelper
{
    public static function normalize(string $value): string
    {
        return str_replace(['/', '\\'], '-', $value);
    }
}
