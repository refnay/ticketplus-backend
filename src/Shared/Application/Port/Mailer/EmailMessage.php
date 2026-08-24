<?php

namespace App\Shared\Application\Port\Mailer;

final readonly class EmailMessage
{
    public function __construct(
        private string $fromAddress,
        private string $fromName,
        private string $to,
        private string $subject,
        private string $template,
        private array $context = [],
        private array $attachments = [],
    ) {
    }

    public function fromAddress(): string
    {
        return $this->fromAddress;
    }

    public function fromName(): string
    {
        return $this->fromName;
    }

    public function to(): string
    {
        return $this->to;
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function template(): string
    {
        return $this->template;
    }

    /** @return array<string, mixed> */
    public function context(): array
    {
        return $this->context;
    }

    /** @return EmailAttachment[] */
    public function attachments(): array
    {
        return $this->attachments;
    }
}
