<?php

namespace App\Catalog\Category\Application\Choose;

use App\Shared\Application\Security\AuthorizationContext;

class ChooseCategoriesQueryHandler
{
    public function __construct(
        private AuthorizationContext $authorization,
        private CategoryChooser $chooser,
    ) {}

    public function __invoke(ChooseCategoriesQuery $query): CategoryChoicesResponse
    {
        return $this->chooser->__invoke(
            $query->filters($this->authorization->companyId()),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}
