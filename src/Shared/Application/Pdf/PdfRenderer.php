<?php

namespace App\Shared\Application\Pdf;

interface PdfRenderer
{
    public function render(string $html): string;
}