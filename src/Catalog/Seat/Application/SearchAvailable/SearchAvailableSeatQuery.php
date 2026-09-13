<?php

namespace App\Catalog\Seat\Application\SearchAvailable;

use App\Shared\Application\Input\PayloadMapper;
use App\Shared\Application\Query\SearchQuery;

class SearchAvailableSeatQuery extends SearchQuery
{
    public function __construct(
        private string $zone,
        private ?string $code,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $page,
    ) {
        parent::__construct($orderBy, $order, $limit, $page);
    }

    public static function fromQuery(string $zone, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $zone,
            $payload->nullableString('code'),
            $payload->string('orderBy'),
            $payload->string('order'),
            $payload->nullableInt('limit'),
            $payload->nullableInt('page'),
        );
    }

    public function zone(): string
    {
        return $this->zone;
    }

    public function filters(): array
    {
        return get_object_vars($this);
    }
}
