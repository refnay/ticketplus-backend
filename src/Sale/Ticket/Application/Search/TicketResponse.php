<?php

namespace App\Sale\Ticket\Application\Search;

use App\Sale\Ticket\Domain\Ticket;
use JsonSerializable;
use Override;

class TicketResponse implements JsonSerializable
{
    public function __construct(
        private string $id,
        private string $code,
        private string $qrCode,
        private array $information,
        private float $price,
        private int $status,
    ) {}

    public static function create(Ticket $ticket): self
    {
        return new self(
            $ticket->id()->value(),
            $ticket->code()->value(),
            $ticket->qrCode()->value(),
            $ticket->information()->toArray(),
            $ticket->price()->value(),
            $ticket->status()->value(),
        );
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}