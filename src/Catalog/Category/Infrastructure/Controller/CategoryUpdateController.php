<?php

namespace App\Catalog\Category\Infrastructure\Controller;

use App\Catalog\Category\Application\Update\UpdateCategoryCommand;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CategoryUpdateController extends AbstractController
{
    public function update(string $id, Request $request, CommandBus $commandBus): JsonResponse
    {

        $command = UpdateCategoryCommand::create($id, $request->toArray());

        $commandBus->dispatch($command);

        return new JsonResponse([]);
    }
}
