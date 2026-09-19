<?php

namespace App\Sale\Ticket\Application\FindByKey;

use App\Sale\Reference\User\Domain\User;
use App\Sale\Ticket\Domain\Ticket;
use JsonSerializable;
use Override;

final readonly class TicketByKeyResponse implements JsonSerializable
{
    public function __construct(
        private string $id,
        private string $code,
        private string $qrCode,
        private array $information,
        private float $price,
        private int $status,
        private ?string $validatedAt,
        private ?array $validatedBy,
    ) {}

    public static function create(Ticket $ticket, ?User $user): self
    {
        return new self(
            $ticket->id()->value(),
            $ticket->code()->value(),
            $ticket->qrCode()->value(),
            $ticket->information()->toArray(),
            $ticket->price()->value(),
            $ticket->status()->value(),
            !$ticket->validatedAt()->isNull() ? $ticket->validatedAt()->asDMYHMS() : null,
            !is_null($user) ? $user->toArray() : null,
        );
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
