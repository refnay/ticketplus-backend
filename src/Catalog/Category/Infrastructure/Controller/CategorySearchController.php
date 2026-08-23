<?php

namespace App\Catalog\Category\Infrastructure\Controller;

use App\Catalog\Category\Application\Search\CategoriesResponse;
use App\Catalog\Category\Application\Search\SearchCategoryQuery;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CategorySearchController extends AbstractController
{
    public function search(Request $request, Session $session, MessageBus $messageBus): JsonResponse
    {
        $session->allPermissions();

        $query = SearchCategoryQuery::fromQuery($request->query->all());
        $query->setSession($session);

        /** @var CategoriesResponse $response */
        $response = $messageBus->ask($query);

        return new JsonResponse($response->jsonSerialize());
    }
}
