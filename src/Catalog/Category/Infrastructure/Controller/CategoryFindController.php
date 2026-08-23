<?php

namespace App\Catalog\Category\Infrastructure\Controller;

use App\Catalog\Category\Application\Find\CategoryResponse;
use App\Catalog\Category\Application\Find\FindCategoryQuery;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryFindController extends AbstractController
{
    public function find(string $id, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $query = FindCategoryQuery::create($id);
        $query->setSession($session);

        /** @var CategoryResponse $response */
        $response = $messageBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
