<?php

namespace App\Account\Company\Infrastructure\Controller;

use App\Account\Company\Application\Update\UpdateCompanyCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CompanyUpdateController extends AbstractController
{
    public function update(Request $request, CommandBus $commandBus): JsonResponse
    {
        $command = UpdateCompanyCommand::create($request->toArray());

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
