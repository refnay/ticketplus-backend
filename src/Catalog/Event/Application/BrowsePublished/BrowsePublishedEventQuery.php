<?php

namespace App\Catalog\Event\Application\BrowsePublished;

use App\Catalog\Event\Domain\EventStatusList;
use App\Shared\Application\Input\PayloadMapper;
use App\Shared\Application\Query\SearchQuery;

class BrowsePublishedEventQuery extends SearchQuery
{
    public function __construct(
        private ?string $value,
        private ?string $country,
        private ?string $city,
        private ?string $category,
        private ?string $date,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $page,
    ) {
        parent::__construct($orderBy, $order, $limit, $page);
    }

    public static function fromQuery(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $payload->nullableString('value'),
            $payload->nullableString('country'),
            $payload->nullableString('city'),
            $payload->nullableString('category'),
            $payload->nullableString('date'),
            $payload->string('orderBy'),
            $payload->string('order'),
            $payload->nullableInt('limit'),
            $payload->nullableInt('page'),
        );
    }

    public function filters(): array
    {
        $filters = get_object_vars($this);
        $filters['status'] = EventStatusList::PUBLISHED->value;

        return $filters;
    }
}
