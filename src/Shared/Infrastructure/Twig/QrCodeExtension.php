<?php

namespace App\Shared\Infrastructure\Twig;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class QrCodeExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [new TwigFunction('qr_code', [$this, 'generate'])];
    }

    public function generate(string $value): string
    {
        $result = new Builder(writer: new PngWriter(), data: $value, size: 300, margin: 0)->build();

        return 'data:image/png;base64,' . base64_encode($result->getString());
    }
}