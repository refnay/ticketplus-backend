<?php

namespace App\Sale\Ticket\Application\OnTicketCreated;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Reference\User\Domain\Services\UserFinder;
use App\Sale\Reference\User\Domain\UserId;
use App\Sale\Ticket\Application\Render\TicketRender;
use App\Sale\Ticket\Domain\TicketId;
use App\Shared\Application\Port\Mailer\EmailAttachment;
use App\Shared\Application\Port\Mailer\EmailMessage;
use App\Shared\Application\Port\Mailer\Mailer;
use App\Shared\Application\Support\ArrayBuilder;

final readonly class TicketSender
{
    public function __construct(
        private TicketRender $ticketRender,
        private UserFinder $userFinder,
        private Mailer $mailer,
    ) {
    }

    public function __invoke(array $ticketIds, OrderId $orderId, UserId $userId): void
    {
        if (empty($ticketIds)) {
            return;
        }

        $user = $this->userFinder->__invoke($userId);

        $attachments = ArrayBuilder::generate();

        foreach ($ticketIds as $ticketId) {
            $ticket = $this->ticketRender->__invoke(
                TicketId::fromString($ticketId),
                $userId,
            );

            $attachments->add(new EmailAttachment(
                $ticket->content(),
                $ticket->filename(),
                'application/pdf',
            ));
        }

        $this->mailer->send(new EmailMessage(
            'no-reply@ticketplus.com',
            'Ticketplus',
            $user->email(),
            'Confirmación de compra',
            'email/tickets-email.html.twig',
            ['name' => $user->name()],
            $attachments->items(),
        ));
    }
}
