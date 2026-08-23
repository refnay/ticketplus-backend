<?php

namespace App\Shared\Domain\Pdf;

interface PdfRenderer
{
    public function render(string $html): string;
}
