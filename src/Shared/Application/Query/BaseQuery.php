<?php

namespace App\Shared\Application\Query;

use App\Shared\Domain\Session;

class BaseQuery
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