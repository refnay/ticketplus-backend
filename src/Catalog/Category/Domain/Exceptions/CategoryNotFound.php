<?php

namespace App\Catalog\Category\Domain\Exceptions;

use Exception;
use Throwable;

class CategoryNotFound extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('category.category_not_found', 0, $previous);
    }
}