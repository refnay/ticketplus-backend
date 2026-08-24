<?php

namespace App\Sale\User\Infrastructure;

use App\Sale\User\Domain\User;
use App\Sale\User\Domain\UserId;
use App\Sale\User\Domain\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Override;

class UserDoctrineRepository implements UserRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Override]
    public function findById(UserId $id): ?User
    {
        $sql = 'SELECT
                    u.id,
                    u.name,
                    u.last_name,
                    u.email
                FROM "user" u
                WHERE u.id = :id';

        $result = $this->entityManager
            ->getConnection()
            ->executeQuery($sql, ['id' => $id->value()])
            ->fetchAssociative();

        if (!is_array($result)) {
            return null;
        }

        return User::create(
            (string) $result['id'],
            (string) $result['name'],
            (string) $result['last_name'],
            (string) $result['email'],
        );
    }
}
