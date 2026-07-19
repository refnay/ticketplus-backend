<?php

namespace App\Shared\Domain\Utils;

class ArrayHelper
{
    public static function chooser(mixed $code, mixed $label): array
    {
        return [
            'code' => $code,
            'label' => $label,
        ];
    }
}