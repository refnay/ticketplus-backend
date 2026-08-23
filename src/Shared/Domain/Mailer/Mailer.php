<?php

namespace App\Shared\Domain\Mailer;

interface Mailer
{
    public function send(EmailMessage $message): void;
}
