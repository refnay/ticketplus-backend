<?php

namespace App\Shared\Application\Transaction;

interface TransactionService
{
    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;
}
