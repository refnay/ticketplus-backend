<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\UploadCoverImage\UploadEventCoverImageCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EventCoverImageUploadController extends AbstractController
{
    public function upload(string $id, Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $command = new UploadEventCoverImageCommand($id, $request->files->get('coverImage'));
        $command->setSession($session);

        $messageBus->dispatch($command);

        return new JsonResponse([]);
    }
}
