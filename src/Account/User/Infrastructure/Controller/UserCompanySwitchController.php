<?php

namespace App\Account\User\Infrastructure\Controller;

use App\Account\User\Application\SwitchCompany\SwitchUserCompanyCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserCompanySwitchController extends AbstractController
{
    public function switch(Request $request, CommandBus $commandBus): JsonResponse
    {
        
        $command = SwitchUserCompanyCommand::create($request->toArray());
        
        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
