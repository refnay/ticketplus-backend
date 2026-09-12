<?php

namespace App\Catalog\Seat\Application\Search;

use App\Shared\Application\Query\SearchQuery;
use App\Shared\Application\Input\PayloadMapper;

class SearchSeatQuery extends SearchQuery
{
    public function __construct(
        private ?string $zone,
        private ?string $code,
        private ?bool $numberedSeating,
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
            $payload->nullableString('zone'),
            $payload->nullableString('code'),
            $payload->nullableBoolFromString('numberedSeating'),
            $payload->string('orderBy'),
            $payload->string('order'),
            $payload->nullableInt('limit'),
            $payload->nullableInt('page') ,
        );
    }

    public function numberedSeating(): ?bool
    {
        return $this->numberedSeating;
    }

    public function filters(string $companyId): array
    {
        $filters = get_object_vars($this);
        $filters['company'] = $companyId;

        return $filters;
    }
}
