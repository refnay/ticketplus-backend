<?php

namespace App\Shared\Application\Port\Pdf;

interface PdfRenderer
{
    public function render(string $html): string;
}
