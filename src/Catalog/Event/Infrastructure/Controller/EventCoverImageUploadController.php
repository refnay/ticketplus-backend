<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\UploadCoverImage\UploadEventCoverImageCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EventCoverImageUploadController extends AbstractController
{
    public function upload(string $id, Request $request, CommandBus $commandBus): JsonResponse
    {

        $command = new UploadEventCoverImageCommand($id, $request->files->get('coverImage'));

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
