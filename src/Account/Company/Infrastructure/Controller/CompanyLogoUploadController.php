<?php

namespace App\Account\Company\Infrastructure\Controller;

use App\Account\Company\Application\UploadLogo\UploadCompanyLogoCommand;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Infrastructure\Http\FileUploadFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CompanyLogoUploadController extends AbstractController
{
    public function upload(Request $request, CommandBus $commandBus): JsonResponse
    {
        $command = new UploadCompanyLogoCommand(FileUploadFactory::fromRequest($request->files->get('logo')));

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
