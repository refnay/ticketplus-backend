<?php

namespace App\Shared\Infrastructure\Template;

use App\Shared\Application\Pdf\TemplateRenderer;
use Twig\Environment;

final readonly class TwigTemplateRenderer implements TemplateRenderer
{
    public function __construct(private Environment $twig)
    {
    }

    public function render(string $template, array $data): string
    {
        return $this->twig->render($template, $data);
    }
}