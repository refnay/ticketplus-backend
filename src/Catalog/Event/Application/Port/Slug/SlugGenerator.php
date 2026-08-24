<?php

namespace App\Catalog\Event\Application\Port\Slug;

interface SlugGenerator
{
    public function generate(string $value): string;
}
