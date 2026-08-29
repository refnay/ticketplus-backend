<?php

namespace App\Catalog\Event\Infrastructure\Controller;

use App\Catalog\Event\Application\UploadLogo\UploadEventLogoCommand;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Infrastructure\Http\FileBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EventLogoUploadController extends AbstractController
{
    public function upload(string $id, Request $request, CommandBus $commandBus): JsonResponse
    {
        $commandBus->dispatch(new UploadEventLogoCommand(
            $id,
            FileBuilder::fromRequest($request->files->get('logo')),
        ));

        return new JsonResponse([]);
    }
}
