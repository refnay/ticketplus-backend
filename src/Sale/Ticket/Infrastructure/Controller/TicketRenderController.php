<?php

namespace App\Sale\Ticket\Infrastructure\Controller;

use App\Sale\Ticket\Application\Render\RenderTicketQuery;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Component\HttpFoundation\Response;

class TicketRenderController
{
    public function render(string $id, string $order, Session $session, MessageBus $messageBus): Response
    {
        $query = RenderTicketQuery::create($id, $order);
        $query->setSession($session);

        /** @var string $response */
        $response = $messageBus->ask($query);

        return new Response($response, headers: ['Content-Type' => 'application/pdf']);
    }
}
