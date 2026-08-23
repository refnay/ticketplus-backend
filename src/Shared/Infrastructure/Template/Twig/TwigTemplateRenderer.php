<?php

namespace App\Shared\Infrastructure\Template\Twig;

use App\Shared\Application\Template\TemplateRenderer;
use Twig\Environment;

final readonly class TwigTemplateRenderer implements TemplateRenderer
{
    public function __construct(private Environment $twig)
    {
    }

    public function render(string $template, array $parameters): string
    {
        return $this->twig->render($template, $parameters);
    }
}