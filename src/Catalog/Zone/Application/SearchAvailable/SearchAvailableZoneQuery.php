<?php

namespace App\Catalog\Zone\Application\SearchAvailable;

use App\Shared\Application\Input\PayloadMapper;
use App\Shared\Application\Query\SearchQuery;

class SearchAvailableZoneQuery extends SearchQuery
{
    public function __construct(
        private string $day,
        private ?string $name,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $page,
    ) {
        parent::__construct($orderBy, $order, $limit, $page);
    }

    public static function fromQuery(string $day, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $day,
            $payload->nullableString('name'),
            $payload->string('orderBy'),
            $payload->string('order'),
            $payload->nullableInt('limit'),
            $payload->nullableInt('page'),
        );
    }

    public function day(): string
    {
        return $this->day;
    }

    public function filters(): array
    {
        return get_object_vars($this);
    }
}
