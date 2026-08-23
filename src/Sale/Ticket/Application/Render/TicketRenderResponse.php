<?php

namespace App\Sale\Ticket\Application\Render;

final readonly class TicketRenderResponse
{
    public function __construct(
        private string $content,
        private string $filename,
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
}
