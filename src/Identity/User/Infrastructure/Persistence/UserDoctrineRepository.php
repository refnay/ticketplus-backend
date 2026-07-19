<?php

namespace App\Identity\User\Infrastructure\Persistence;

use App\Identity\User\Domain\Exceptions\UserNotCreated;
use App\Identity\User\Domain\Exceptions\UserNotDeleted;
use App\Identity\User\Domain\Exceptions\UserNotUpdated;
use App\Identity\User\Domain\User;
use App\Identity\User\Domain\UserDocument;
use App\Identity\User\Domain\UserEmail;
use App\Identity\User\Domain\UserId;
use App\Identity\User\Domain\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Throwable;

class UserDoctrineRepository implements UserRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserMapper $mapper,
        private JWTTokenManagerInterface $jwtManager,
    ) {
    }
    
    public function save(User $user): string
    {
        try {
            $entity = $this->mapper->newEntity($user);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            return $this->jwtManager->create($entity);
        } catch (Throwable) {
            throw new UserNotCreated();
        }
    }

    public function update(User $user): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $user->id()->value());
            $this->mapper->update($entity, $user);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new UserNotUpdated();
        }
    }

    public function delete(User $user): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $user->id()->value());
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new UserNotDeleted();
        }
    }

    public function findById(UserId $id): ?User
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->find($id->value());

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    public function findByEmail(UserEmail $email): ?User
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->findOneBy(['email' => $email->value()]);

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    public function findByDocument(UserDocument $document): ?User
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->findOneBy(['documentType' => $document->type(), 'documentNumber' => $document->number()]);

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }
}