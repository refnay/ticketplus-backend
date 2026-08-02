<?php

namespace App\Account\Company\Domain;

interface CompanyRepository
{
    public function save(Company $company): void;

    public function update(Company $company): void;

    public function delete(Company $company): void;

    public function findById(CompanyId $id): ?Company;

    public function findByDocument(CompanyDocument $document): ?Company;
}