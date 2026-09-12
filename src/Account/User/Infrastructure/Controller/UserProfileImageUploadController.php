<?php

namespace App\Account\User\Infrastructure\Controller;

use App\Account\User\Application\UploadProfileImage\UploadUserProfileImageCommand;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Infrastructure\Http\FileBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserProfileImageUploadController extends AbstractController
{
    public function upload(Request $request, CommandBus $commandBus): JsonResponse
    {
        $command = new UploadUserProfileImageCommand(
            FileBuilder::fromRequest($request->files->get('profileImage')),
        );

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
