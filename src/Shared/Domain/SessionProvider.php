<?php

namespace App\Shared\Domain;

interface SessionProvider
{
    public function user(): string;
}