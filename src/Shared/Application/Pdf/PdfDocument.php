<?php

namespace App\Shared\Application\Pdf;

final readonly class PdfDocument
{
    public function __construct(
        private string $paper = 'A4',
        private string $orientation = 'portrait',
        private string $title = 'Document',
    ) {
    }

    public function paper(): string
    {
        return $this->paper;
    }

    public function orientation(): string
    {
        return $this->orientation;
    }

    public function title(): string
    {
        return $this->title;
    }
}