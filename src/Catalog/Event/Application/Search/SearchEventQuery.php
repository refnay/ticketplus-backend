<?php

namespace App\Catalog\Event\Application\Search;

use App\Shared\Application\Query\SearchQuery;
use App\Shared\Application\Input\PayloadMapper;

class SearchEventQuery extends SearchQuery
{
    public function __construct(
        private ?string $value,
        private ?string $country,
        private ?string $city,
        private ?string $category,
        private ?string $date,
        private ?int $status,
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
            $payload->nullableInt('status'),
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
