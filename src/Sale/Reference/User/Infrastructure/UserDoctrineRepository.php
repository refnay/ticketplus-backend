<?php

namespace App\Sale\Reference\User\Infrastructure;

use App\Sale\Reference\User\Domain\User;
use App\Sale\Reference\User\Domain\UserId;
use App\Sale\Reference\User\Domain\UserRepository;
use App\Shared\Infrastructure\Persistence\Doctrine\NativeQueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Override;

class UserDoctrineRepository implements UserRepository
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Override]
    public function findById(UserId $id): ?User
    {
        $result = NativeQueryBuilder::from($this->entityManager->getConnection(), '"user"', 'u')
            ->select('u.id', 'u.name', 'u.last_name', 'u.email')
            ->equals('id', $id->value())
            ->fetchAssociative();

        if (is_null($result)) {
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
