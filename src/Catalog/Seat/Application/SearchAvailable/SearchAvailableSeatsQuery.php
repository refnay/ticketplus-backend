<?php

namespace App\Catalog\Seat\Application\SearchAvailable;

use App\Catalog\Seat\Domain\SeatStatusList;
use App\Shared\Application\Input\PayloadMapper;
use App\Shared\Application\Query\SearchQuery;

class SearchAvailableSeatsQuery extends SearchQuery
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
        $order = strtoupper($payload->nullableString('order') ?? 'ASC');
        $limit = $payload->nullableInt('limit') ?? 100;
        $page = $payload->nullableInt('page') ?? 1;

        return new self(
            $zone,
            $payload->nullableString('code'),
            'code',
            in_array($order, ['ASC', 'DESC'], true) ? $order : 'ASC',
            min(max($limit, 1), 500),
            max($page, 1),
        );
    }

    public function zone(): string
    {
        return $this->zone;
    }

    public function filters(): array
    {
        return [
            'zone' => $this->zone,
            'code' => $this->code,
            'status' => SeatStatusList::AVAILABLE->value,
        ];
    }
}
