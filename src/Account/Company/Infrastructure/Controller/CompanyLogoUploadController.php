<?php

namespace App\Account\Company\Infrastructure\Controller;

use App\Account\Company\Application\UploadLogo\UploadCompanyLogoCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CompanyLogoUploadController extends AbstractController
{
    public function upload(Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $command = new UploadCompanyLogoCommand($request->files->get('logo'));
        $command->setSession($session);

        $messageBus->dispatch($command);

        return new JsonResponse([]);
    }
}
