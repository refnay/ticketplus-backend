<?php

namespace App\Shared\Application\Transaction;

interface TransactionManager
{
    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;
}
