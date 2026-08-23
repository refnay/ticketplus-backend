<?php

namespace App\Shared\Domain\Template;

interface TemplateRenderer
{
    public function render(string $template, array $data): string;
}
