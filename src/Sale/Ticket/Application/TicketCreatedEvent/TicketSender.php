<?php

namespace App\Sale\Ticket\Application\TicketCreatedEvent;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Shared\Domain\Services\UserFinder;
use App\Sale\Shared\Domain\UserId;
use App\Sale\Ticket\Application\Render\TicketRender;
use App\Sale\Ticket\Domain\TicketId;
use App\Shared\Domain\Mailer\EmailAttachment;
use App\Shared\Domain\Mailer\EmailMessage;
use App\Shared\Domain\Mailer\Mailer;

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

        $attachments = [];

        foreach ($ticketIds as $ticketId) {
            $ticket = $this->ticketRender->__invoke(
                TicketId::fromString($ticketId),
                $orderId,
                $userId,
            );

            $attachments[] = new EmailAttachment(
                $ticket->content(),
                $ticket->filename(),
                'application/pdf',
            );
        }

        $this->mailer->send(new EmailMessage(
            fromAddress: 'no-reply@ticketplus.com',
            fromName: 'Ticketplus',
            to: $user->email(),
            subject: 'Confirmación de compra',
            template: 'email/tickets-email.html.twig',
            context: ['name' => $user->name()],
            attachments: $attachments,
        ));
    }
}
