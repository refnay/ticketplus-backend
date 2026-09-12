<?php

namespace App\Catalog\Event\Application\BrowsePublished;

use App\Catalog\Event\Domain\EventStatusList;
use App\Shared\Application\Input\PayloadMapper;
use App\Shared\Application\Query\SearchQuery;

class BrowsePublishedEventsQuery extends SearchQuery
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
        $orderBy = $payload->nullableString('orderBy');
        $order = strtoupper($payload->nullableString('order') ?? 'DESC');
        $limit = $payload->nullableInt('limit') ?? 10;
        $page = $payload->nullableInt('page') ?? 1;

        return new self(
            $payload->nullableString('value'),
            $payload->nullableString('country'),
            $payload->nullableString('city'),
            $payload->nullableString('category'),
            $payload->nullableString('date'),
            in_array($orderBy, ['createdAt', 'name', 'city'], true) ? $orderBy : 'createdAt',
            in_array($order, ['ASC', 'DESC'], true) ? $order : 'DESC',
            min(max($limit, 1), 100),
            max($page, 1),
        );
    }

    public function filters(): array
    {
        return [
            'value' => $this->value,
            'country' => $this->country,
            'city' => $this->city,
            'category' => $this->category,
            'date' => $this->date,
            'status' => EventStatusList::PUBLISHED->value,
        ];
    }
}
