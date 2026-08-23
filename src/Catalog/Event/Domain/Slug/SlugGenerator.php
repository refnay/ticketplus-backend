<?php

namespace App\Catalog\Event\Domain\Slug;

interface SlugGenerator
{
    public function generate(string $value): string;
}
