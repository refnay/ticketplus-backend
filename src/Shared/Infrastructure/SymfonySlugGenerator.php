<?php

namespace App\Shared\Infrastructure;

use App\Shared\Domain\Services\SlugGenerator;
use Override;
use Symfony\Component\String\Slugger\SluggerInterface;

class SymfonySlugGenerator implements SlugGenerator
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    #[Override]
    public function generate(string $value): string
    {
        return strtolower($this->slugger->slug($value)->toString());
    }
}