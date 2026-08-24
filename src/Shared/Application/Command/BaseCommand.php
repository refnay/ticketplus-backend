<?php

namespace App\Shared\Application\Command;

use App\Shared\Domain\Session\Session;

class BaseCommand
{
    private Session $session;

    public function setSession(Session $session): void
    {
        $this->session = $session;
    }

    public function session(): Session
    {
        return $this->session;
    }
}
