<?php

namespace App\Account\Company\Infrastructure\Controller;

use App\Account\Company\Application\Create\CreateCompanyCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CompanyCreateController extends AbstractController
{
    public function create(Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $command = CreateCompanyCommand::create($request->toArray());
        $command->setSession($session);
        
        /** @var string $id */
        $id = $messageBus->dispatch($command);

        return new JsonResponse(['id' => $id]);
    }
}