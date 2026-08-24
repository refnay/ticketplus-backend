<?php

namespace App\Catalog\Category\Infrastructure\Controller;

use App\Catalog\Category\Application\Search\CategoriesResponse;
use App\Catalog\Category\Application\Search\SearchCategoryQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CategorySearchController extends AbstractController
{
    public function search(Request $request, QueryBus $queryBus): JsonResponse
    {
        $query = SearchCategoryQuery::fromQuery($request->query->all());

        /** @var CategoriesResponse $response */
        $response = $queryBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
