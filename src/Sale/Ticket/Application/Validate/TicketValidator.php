<?php

namespace App\Sale\Ticket\Application\Validate;

use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Ticket\Domain\Exceptions\TicketAlreadyValidated;
use App\Sale\Ticket\Domain\Exceptions\TicketNotValidToday;
use App\Sale\Ticket\Domain\Services\TicketByKeyFinder;
use App\Sale\Ticket\Domain\TicketRepository;
use App\Sale\Ticket\Domain\TicketValidatedBy;

final readonly class TicketValidator
{
    public function __construct(
        private TicketByKeyFinder $finder,
        private TicketRepository $repository,
    ) {}

    public function __invoke(string $key, CompanyId $companyId, TicketValidatedBy $userId): void
    {
        $ticket = $this->finder->__invoke($key, $companyId);

        if (!$ticket->status()->isActive()) {
            throw new TicketAlreadyValidated();
        }

        if (!$ticket->information()->isToday()) {
            throw new TicketNotValidToday();
        }

        $ticket->validate($userId);

        $this->repository->update($ticket);
    }
}