<?php

namespace App\Sale\Ticket\Domain\Services;

use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Ticket\Domain\Exceptions\TicketNotFound;
use App\Sale\Ticket\Domain\Ticket;
use App\Sale\Ticket\Domain\TicketCode;
use App\Sale\Ticket\Domain\TicketQRCode;
use App\Sale\Ticket\Domain\TicketRepository;
use Symfony\Component\Uid\Uuid;

class TicketByKeyFinder
{
    public function __construct(private TicketRepository $repository) {}

    public function __invoke(string $key, CompanyId $companyId): Ticket
    {
        $ticket = Uuid::isValid($key)
            ? $this->repository->findByQrCode(TicketQRCode::fromString($key), $companyId)
            : $this->repository->findByCode(TicketCode::fromString($key), $companyId);

        if (is_null($ticket)) {
            throw new TicketNotFound();
        }

        return $ticket;
    }
}
