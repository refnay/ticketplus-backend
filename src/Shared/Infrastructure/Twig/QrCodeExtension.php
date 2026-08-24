<?php

namespace App\Shared\Infrastructure\Twig;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\SvgWriter;
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
        $result = (new Builder(writer: new SvgWriter(), data: $value, size: 300, margin: 0))->build();

        return $result->getDataUri();
    }
}
