<?php

namespace App\Catalog\Zone\Application\SearchAvailable;

use App\Shared\Application\Input\PayloadMapper;
use App\Shared\Application\Query\SearchQuery;

class SearchAvailableZonesQuery extends SearchQuery
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
        $orderBy = $payload->nullableString('orderBy');
        $order = strtoupper($payload->nullableString('order') ?? 'ASC');
        $limit = $payload->nullableInt('limit') ?? 100;
        $page = $payload->nullableInt('page') ?? 1;

        return new self(
            $day,
            $payload->nullableString('name'),
            in_array($orderBy, ['hierarchy', 'name', 'price'], true) ? $orderBy : 'hierarchy',
            in_array($order, ['ASC', 'DESC'], true) ? $order : 'ASC',
            min(max($limit, 1), 100),
            max($page, 1),
        );
    }

    public function day(): string
    {
        return $this->day;
    }

    public function filters(): array
    {
        return ['day' => $this->day, 'name' => $this->name, 'availableOnly' => true];
    }
}
