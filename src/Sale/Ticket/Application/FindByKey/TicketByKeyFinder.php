<?php

namespace App\Sale\Ticket\Application\FindByKey;

use App\Sale\Reference\User\Domain\Exceptions\UserNotFound;
use App\Sale\Reference\User\Domain\Services\UserFinder;
use App\Sale\Reference\User\Domain\UserId;
use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Ticket\Domain\Services\TicketByKeyFinder as ServiceTicketByKeyFinder;

final readonly class TicketByKeyFinder
{
    public function __construct(
        private ServiceTicketByKeyFinder $ticketFinder,
        private UserFinder $userFinder,
    ) {}

    public function __invoke(string $key, CompanyId $companyId): TicketByKeyResponse
    {
        $ticket = $this->ticketFinder->__invoke($key, $companyId);
        $user = null;

        try {
            $user = $this->userFinder->__invoke(UserId::fromString($ticket->validatedBy()->value()));
        } catch (UserNotFound) {
        }

        return TicketByKeyResponse::create($ticket, $user);
    }
}
