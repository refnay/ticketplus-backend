<?php

namespace App\Catalog\Event\Application\Choose;

use App\Shared\Application\Security\AuthorizationContext;

class ChooseEventsQueryHandler
{
    public function __construct(
        private AuthorizationContext $authorization,
        private EventChooser $chooser,
    ) {}

    public function __invoke(ChooseEventsQuery $query): EventChoicesResponse
    {
        return $this->chooser->__invoke($this->authorization->companyId());
    }
}
