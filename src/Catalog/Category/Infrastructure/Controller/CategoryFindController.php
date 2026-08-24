<?php

namespace App\Catalog\Category\Infrastructure\Controller;

use App\Catalog\Category\Application\Find\CategoryResponse;
use App\Catalog\Category\Application\Find\FindCategoryQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryFindController extends AbstractController
{
    public function find(string $id, QueryBus $queryBus): JsonResponse
    {

        $query = FindCategoryQuery::create($id);

        /** @var CategoryResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
