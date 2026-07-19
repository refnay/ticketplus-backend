<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\TransactionService;
use Doctrine\ORM\EntityManagerInterface;

final class TransactionManager implements TransactionService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function begin(): void
    {
        $this->em->getConnection()->beginTransaction();
    }

    public function commit(): void
    {
        $this->em->flush();
        $this->em->getConnection()->commit();
    }

    public function rollback(): void
    {
        $this->em->getConnection()->rollBack();
    }
}
