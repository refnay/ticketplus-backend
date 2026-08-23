<?php

namespace App\Catalog\Event\Infrastructure\Slug;

use App\Catalog\Event\Domain\Slug\SlugGenerator;
use Symfony\Component\String\Slugger\SluggerInterface;

final readonly class SymfonySlugGenerator implements SlugGenerator
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function generate(string $value): string
    {
        return strtolower($this->slugger->slug($value)->toString());
    }
}
