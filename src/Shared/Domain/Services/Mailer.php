<?php

namespace App\Shared\Domain\Services;

use Symfony\Component\Mime\Email;

interface Mailer
{
    public function send(Email $email): void;
}