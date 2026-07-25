<?php

namespace App\Account\User\Infrastructure\Controller;

use App\Account\User\Application\UploadProfileImage\UploadUserProfileImageCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserProfileImageUploadController extends AbstractController
{
    public function upload(Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $command = new UploadUserProfileImageCommand($request->files->get('profileImage'));
        $command->setSession($session);
        
        $messageBus->dispatch($command);

        return new JsonResponse([]);
    }
}