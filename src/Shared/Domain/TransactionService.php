<?php

namespace App\Shared\Domain;

interface TransactionService
{
    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;
}