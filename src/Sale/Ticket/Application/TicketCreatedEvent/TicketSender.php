<?php

namespace App\Sale\Ticket\Application\TicketCreatedEvent;

use App\Sale\Order\Domain\OrderId;
use App\Sale\User\Domain\Services\UserFinder;
use App\Sale\User\Domain\UserId;
use App\Sale\Ticket\Application\Render\TicketRender;
use App\Sale\Ticket\Domain\TicketId;
use App\Shared\Domain\Mailer\EmailAttachment;
use App\Shared\Domain\Mailer\EmailMessage;
use App\Shared\Domain\Mailer\Mailer;
use App\Shared\Domain\Utils\Primitive\ArrayBuilder;

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
                $orderId,
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
