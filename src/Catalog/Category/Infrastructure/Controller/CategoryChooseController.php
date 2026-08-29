<?php

namespace App\Catalog\Category\Infrastructure\Controller;

use App\Catalog\Category\Application\Choose\CategoryChoicesResponse;
use App\Catalog\Category\Application\Choose\ChooseCategoriesQuery;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryChooseController extends AbstractController
{
    public function choose(QueryBus $queryBus): JsonResponse
    {
        /** @var CategoryChoicesResponse $response */
        $response = $queryBus->ask(ChooseCategoriesQuery::create());

        return new JsonResponse($response->jsonSerialize());
    }
}
