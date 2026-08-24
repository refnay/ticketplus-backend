<?php

namespace App\Shared\Infrastructure\Transaction;

use App\Shared\Application\Transaction\TransactionManager;
use App\Shared\Domain\Persistence\TransactionService;

final readonly class DoctrineTransactionManagerAdapter implements TransactionManager
{
    public function __construct(private TransactionService $transaction)
    {
    }

    public function begin(): void
    {
        $this->transaction->begin();
    }

    public function commit(): void
    {
        $this->transaction->commit();
    }

    public function rollback(): void
    {
        $this->transaction->rollback();
    }
}
