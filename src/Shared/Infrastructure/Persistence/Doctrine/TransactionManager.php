<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Application\Transaction\TransactionService;
use Doctrine\ORM\EntityManagerInterface;

final class TransactionManager implements TransactionService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function begin(): void
    {
        $this->entityManager->getConnection()->beginTransaction();
    }

    public function commit(): void
    {
        $this->entityManager->flush();
        $this->entityManager->getConnection()->commit();
    }

    public function rollback(): void
    {
        $this->entityManager->getConnection()->rollBack();
    }
}
