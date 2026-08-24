<?php

namespace App\Shared\Application\Port\Pdf;

interface PdfStorage
{
    public function save(string $content, string $path, string $filename): string;
}
