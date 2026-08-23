<?php

namespace App\Shared\Domain\Mailer;

final readonly class EmailAttachment
{
    public function __construct(
        private string $content,
        private string $filename,
        private string $contentType,
    ) {
    }

    public function content(): string
    {
        return $this->content;
    }

    public function filename(): string
    {
        return $this->filename;
    }

    public function contentType(): string
    {
        return $this->contentType;
    }
}
