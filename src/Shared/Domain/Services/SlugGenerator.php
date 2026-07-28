<?php

namespace App\Shared\Domain\Services;

interface SlugGenerator
{
    public function generate(string $value): string;
}