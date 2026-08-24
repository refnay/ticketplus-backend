<?php

namespace App\Catalog\Category\Infrastructure\Controller;

use App\Catalog\Category\Application\Delete\DeleteCategoryCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryDeleteController extends AbstractController
{
    public function delete(string $id, CommandBus $commandBus): JsonResponse
    {
        $command = DeleteCategoryCommand::create($id);

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
