<?php

namespace App\Catalog\Category\Domain;

class Category
{
    private CategoryId $id;
    private CategoryName $name;
    private CategoryReference $reference;

    public function __construct(CategoryId $id, CategoryName $name, CategoryReference $reference)
    {
        $this->id = $id;
        $this->name = $name;
        $this->reference = $reference;
    }

    public static function create(CategoryName $name, CategoryReference $reference): self
    {
        return new self(CategoryId::generate(), $name, $reference);
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
}
