<?php

namespace App\Shared\Infrastructure\Pdf\Generate;

use App\Shared\Application\Port\Pdf\PdfGenerator;
use App\Shared\Application\Port\Pdf\PdfRenderer;
use App\Shared\Application\Port\Pdf\PdfStorage;
use App\Shared\Application\Port\Template\TemplateRenderer;
use RuntimeException;

final class PdfDocumentGenerator implements PdfGenerator
{
    private array $data = [];
    private ?string $template = null;
    private ?string $path = null;
    private ?string $filename = null;

    public function __construct(
        private readonly TemplateRenderer $templateRenderer,
        private readonly PdfRenderer $pdfRenderer,
        private readonly PdfStorage $pdfStorage,
    ) {
    }

    public function prepare(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = sprintf('%s.pdf', $filename);

        return $this;
    }


    public function filename(): string
    {
        return $this->filename;
    }

    public function generate(): string
    {
        $this->validateTemplate();

        $html = $this->templateRenderer->render($this->template, $this->data);

        return $this->pdfRenderer->render($html);
    }

    public function save(): string
    {
        $this->validateStorage();

        return $this->pdfStorage->save($this->generate(), $this->path, $this->filename);
    }

    private function validateTemplate(): void
    {
        if (is_null($this->template)) {
            throw new RuntimeException('PDF template has not been defined.');
        }
    }

    private function validateStorage(): void
    {
        if (is_null($this->path)) {
            throw new RuntimeException('PDF path has not been defined.');
        }

        if (is_null($this->filename)) {
            throw new RuntimeException('PDF filename has not been defined.');
        }
    }
}
