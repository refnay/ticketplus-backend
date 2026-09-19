<?php

namespace App\Sale\Ticket\Application\Validate;

use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Ticket\Domain\TicketValidatedBy;
use App\Shared\Application\Security\AuthorizationContext;

class ValidateTicketCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private TicketValidator $validator) {}

    public function __invoke(ValidateTicketCommand $command): void
    {
        $this->validator->__invoke(
            $command->key(),
            CompanyId::fromString($this->authorization->companyId()),
            TicketValidatedBy::fromString($this->authorization->userId()),
        );
    }
}
