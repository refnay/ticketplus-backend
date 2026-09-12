<?php

namespace App\Account\Company\Infrastructure\Controller;

use App\Account\Company\Application\Create\CreateCompanyCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CompanyCreateController extends AbstractController
{
    public function create(Request $request, CommandBus $commandBus): JsonResponse
    {
        $command = CreateCompanyCommand::create($request->toArray());

        /** @var string $id */
        $id = $commandBus->dispatch($command);

        return new JsonResponse(['id' => $id]);
    }
}
