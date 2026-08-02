<?php

namespace App\Account\Company\Infrastructure\Persistence;

use App\Account\Company\Domain\Company;
use App\Account\Company\Domain\CompanyDocument;
use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\CompanyRepository;
use App\Account\Company\Domain\Exceptions\CompanyNotCreated;
use App\Account\Company\Domain\Exceptions\CompanyNotDeleted;
use App\Account\Company\Domain\Exceptions\CompanyNotUpdated;
use Doctrine\ORM\EntityManagerInterface;
use Override;
use Throwable;

class CompanyDoctrineRepository implements CompanyRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CompanyMapper $mapper,
    ) {
    }
    
    #[Override]
    public function save(Company $company): void
    {
        try {
            $entity = $this->mapper->newEntity($company);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new CompanyNotCreated();
        }
    }

    #[Override]
    public function update(Company $company): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $company->id()->value());
            $this->mapper->update($entity, $company);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new CompanyNotUpdated();
        }
    }

    #[Override]
    public function delete(Company $company): void
    {
        try {
            $entity = $this->entityManager->getReference($this->mapper->entityClass(), $company->id()->value());
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        } catch (Throwable) {
            throw new CompanyNotDeleted();
        }
    }

    #[Override]
    public function findById(CompanyId $id): ?Company
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->find($id->value());

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }

    #[Override]
    public function findByDocument(CompanyDocument $document): ?Company
    {
        $entity = $this->entityManager
            ->getRepository($this->mapper->entityClass())
            ->findOneBy(['documentType' => $document->type(), 'documentNumber' => $document->number()]);

        return !is_null($entity) ? $this->mapper->newDomain($entity) : null;
    }
}