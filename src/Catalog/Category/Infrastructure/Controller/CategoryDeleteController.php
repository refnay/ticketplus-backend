<?php

namespace App\Catalog\Category\Infrastructure\Controller;

use App\Catalog\Category\Application\Delete\DeleteCategoryCommand;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryDeleteController extends AbstractController
{
    public function delete(string $id, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $command = DeleteCategoryCommand::create($id);
        $command->setSession($session);

        $messageBus->dispatch($command);

        return new JsonResponse([]);
    }
}
