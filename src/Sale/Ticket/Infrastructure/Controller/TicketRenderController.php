<?php

namespace App\Sale\Ticket\Infrastructure\Controller;

use App\Sale\Ticket\Application\Render\RenderTicketQuery;
use App\Sale\Ticket\Application\Render\TicketRenderResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class TicketRenderController extends AbstractController
{
    public function create(string $id, string $order, QueryBus $queryBus): Response
    {
        $query = RenderTicketQuery::create($id, $order);

        /** @var TicketRenderResponse $ticket */
        $ticket = $queryBus->ask($query);
        
        $response = new Response($ticket->content());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set(
            'Content-Disposition',
            $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_INLINE,
                $ticket->filename(),
                'ticket.pdf',
            ),
        );

        return $response;
    }
}
