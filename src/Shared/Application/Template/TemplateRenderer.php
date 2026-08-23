<?php

namespace App\Shared\Application\Template;

interface TemplateRenderer
{
    public function render(string $template, array $data): string;
}