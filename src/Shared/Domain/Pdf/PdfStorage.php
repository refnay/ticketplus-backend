<?php

namespace App\Shared\Domain\Pdf;

interface PdfStorage
{
    public function save(string $content, string $path, string $filename): string;
}
