<?php

namespace App\Shared\Domain\Persistence;

interface TransactionService
{
    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;
}
