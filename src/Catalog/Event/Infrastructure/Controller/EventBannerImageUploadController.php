<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\UploadBannerImage\UploadEventBannerImageCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EventBannerImageUploadController extends AbstractController
{
    public function upload(string $id, Request $request, CommandBus $commandBus): JsonResponse
    {

        $command = new UploadEventBannerImageCommand($id, $request->files->get('bannerImage'));

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
