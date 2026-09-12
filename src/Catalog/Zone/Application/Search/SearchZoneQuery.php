<?php

namespace App\Catalog\Zone\Application\Search;

use App\Shared\Application\Query\SearchQuery;
use App\Shared\Application\Input\PayloadMapper;

class SearchZoneQuery extends SearchQuery
{
    public function __construct(
        private ?string $day,
        private ?string $name,
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
            $payload->nullableString('day'),
            $payload->nullableString('name'),
            $payload->string('orderBy'),
            $payload->string('order'),
            $payload->nullableInt('limit'),
            $payload->nullableInt('page') ,
        );
    }

    public function filters(string $companyId): array
    {
        $filters = get_object_vars($this);
        $filters['company'] = $companyId;

        return $filters;
    }
}
