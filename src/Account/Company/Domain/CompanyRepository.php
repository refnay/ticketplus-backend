<?php

namespace App\Account\Company\Domain;

interface CompanyRepository
{
    public function save(Company $user): string;

    public function update(Company $user): void;

    public function delete(Company $user): void;

    public function findById(CompanyId $id): ?Company;

    public function findByDocument(CompanyDocument $document): ?Company;
}