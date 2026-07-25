<?php

namespace App\Catalog\Category\Domain;

use App\Catalog\Shared\Domain\CompanyId;

interface CategoryRepository
{
    public function save(Category $category): void;

    public function update(Category $category): void;

    public function delete(Category $category): void;

    public function findById(CategoryId $id, CompanyId $companyId): ?Category;
}