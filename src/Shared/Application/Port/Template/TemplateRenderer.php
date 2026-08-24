<?php

namespace App\Shared\Application\Port\Template;

interface TemplateRenderer
{
    public function render(string $template, array $data): string;
}
