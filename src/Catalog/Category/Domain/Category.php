<?php

namespace App\Catalog\Category\Domain;

use App\Catalog\Shared\Domain\CompanyId;

class Category
{
    private CategoryId $id;
    private CategoryName $name;
    private CategoryReference $reference;
    private CompanyId $companyId;

    public function __construct(CategoryId $id, CategoryName $name, CategoryReference $reference, CompanyId $companyId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->reference = $reference;
        $this->companyId = $companyId;
    }

    public static function create(CategoryName $name, CategoryReference $reference, CompanyId $companyId): self
    {
        return new self(CategoryId::generate(), $name, $reference, $companyId);
    }

    public function id(): CategoryId
    {
        return $this->id;
    }

    public function name(): CategoryName
    {
        return $this->name;
    }

    public function reference(): CategoryReference
    {
        return $this->reference;
    }
    
    public function companyId(): CompanyId
    {
        return $this->companyId;
    }
}
