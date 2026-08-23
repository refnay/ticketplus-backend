<?php

namespace App\Sale\Ticket\Application\TicketCreatedEvent;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Shared\Domain\Services\UserFinder;
use App\Sale\Shared\Domain\UserId;
use App\Sale\Ticket\Application\Render\TicketRender;
use App\Sale\Ticket\Domain\TicketId;
use App\Shared\Domain\Services\Mailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

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

        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@ticketplus.com', 'Ticketplus'))
            ->to($user->email())
            ->subject('Confirmación de compra')
            ->htmlTemplate('email/tickets-email.html.twig')
            ->context([
                'name' => $user->name(),
            ]);

        foreach ($ticketIds as $ticketId) {
            $ticket = $this->ticketRender->__invoke(
                TicketId::fromString($ticketId),
                $orderId,
                $userId,
            );

            $email->attach($ticket->content(), $ticket->filename(), 'application/pdf');
        }

        $this->mailer->send($email);
    }
}
