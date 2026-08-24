<?php

namespace App\Shared\Application\Port\Mailer;

interface Mailer
{
    public function send(EmailMessage $message): void;
}
