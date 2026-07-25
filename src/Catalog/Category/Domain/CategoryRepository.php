<?php

namespace App\Catalog\Category\Domain;

interface CategoryRepository
{
    public function save(Category $category): string;

    public function update(Category $category): void;

    public function delete(Category $category): void;

    public function findById(CategoryId $id): ?Category;
}