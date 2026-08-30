<?php

namespace App\Sale\Discount\Application\Search;

use App\Sale\Discount\Domain\Discount;
use App\Sale\Discount\Domain\DiscountRepository;
use App\Sale\Reference\Event\Domain\Services\EventFinder;

class DiscountSearcher
{
    public function __construct(private DiscountRepository $repository, private EventFinder $eventFinder)
    {
    }

    public function __invoke(
        array $filters,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $offset,
    ): DiscountsResponse {
        $discounts = $this->repository->searchByFilters($filters, $orderBy, $order, $limit, $offset);
        $total = $this->repository->countByFilters($filters);

        return new DiscountsResponse($total, ...array_map($this->makeResponse(), $discounts));
    }

    private function makeResponse(): callable
    {
        return function (Discount $discount): DiscountResponse {
            $event = $this->eventFinder->__invoke($discount->eventId());

            return DiscountResponse::create($discount, $event);
        };
    }
}
