<?php

namespace App\Catalog\Category\Infrastructure\Controller;

use App\Catalog\Category\Application\Create\CreateCategoryCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CategoryCreateController extends AbstractController
{
    public function create(Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allowed();

        $command = CreateCategoryCommand::create($request->toArray());
        $command->setSession($session);
        
        /** @var string $id */
        $id = $messageBus->dispatch($command);

        return new JsonResponse(['id' => $id]);
    }
}