<?php

namespace App\Sale\Discount\Application\Search;

use App\Sale\Discount\Domain\Discount;
use App\Sale\Discount\Domain\DiscountRepository;
use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Event\Domain\EventId;
use App\Sale\Event\Domain\Services\EventFinder;

class DiscountSearcher
{
    public function __construct(private DiscountRepository $repository, private EventFinder $eventFinder)
    {
    }

    public function __invoke(
        EventId $eventId,
        CompanyId $companyId,
        array $filters,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $offset,
    ): DiscountsResponse {
        $this->eventFinder->__invoke($eventId, $companyId);

        $discounts = $this->repository->searchByFilters($filters, $orderBy, $order, $limit, $offset);
        $total = $this->repository->countByFilters($filters);

        return new DiscountsResponse($total, ...array_map($this->makeResponse(), $discounts));
    }

    private function makeResponse(): callable
    {
        return fn(Discount $discount) => DiscountResponse::create($discount);
    }
}
