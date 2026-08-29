<?php

namespace App\Catalog\Category\Application\Choose;

use App\Shared\Application\Security\AuthorizationContext;

class ChooseCategoriesQueryHandler
{
    public function __construct(
        private AuthorizationContext $authorization,
        private CategoryChooser $chooser,
    ) {
    }

    public function __invoke(ChooseCategoriesQuery $query): CategoryChoicesResponse
    {
        $this->authorization->requireAllPermissions();

        return $this->chooser->__invoke($this->authorization->requireCompanyId());
    }
}
