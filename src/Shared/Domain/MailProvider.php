<?php

namespace App\Shared\Domain;

use Symfony\Component\Mime\Email;

interface MailProvider
{
    public function send(Email $email): void;
}