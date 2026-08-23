<?php

namespace App\Shared\Infrastructure\Twig;

use RuntimeException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class TicketplusExtension extends AbstractExtension
{
    public function __construct(private readonly string $projectDir)
    {
    }

    public function getFunctions(): array
    {
        return [new TwigFunction('ticketplus_logo', [$this, 'logoDataUri'])];
    }

    public function logoDataUri(): string
    {
        $path = $this->projectDir . '/public/images/logo.png';
        $contents = file_get_contents($path);

        if (false === $contents) {
            throw new RuntimeException(sprintf('Unable to read the brand logo at "%s".', $path));
        }

        return 'data:image/png;base64,' . base64_encode($contents);
    }
}
