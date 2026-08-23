<?php

namespace App\Shared\Application\Pdf;

interface PdfGenerator
{
    public function prepare(array $data): self;

    public function setTemplate(string $template): self;

    public function setPath(string $path): self;

    public function setFilename(string $filename): self;

    public function filename(): string;

    public function generate(): string;

    public function save(): string;
}