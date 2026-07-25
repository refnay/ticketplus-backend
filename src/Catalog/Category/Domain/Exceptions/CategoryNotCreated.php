<?php

namespace App\Catalog\Category\Domain\Exceptions;

use Exception;
use Throwable;

class CategoryNotCreated extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('category.category_not_created', 0, $previous);
    }
}