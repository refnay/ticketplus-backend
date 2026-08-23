<?php

namespace App\Account\Company\Infrastructure\Controller;

use App\Account\Company\Application\Update\UpdateCompanyCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CompanyUpdateController extends AbstractController
{
    public function update(Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $command = UpdateCompanyCommand::create($request->toArray());
        $command->setSession($session);

        $messageBus->dispatch($command);

        return new JsonResponse([]);
    }
}
