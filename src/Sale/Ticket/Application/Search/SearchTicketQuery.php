<?php

namespace App\Sale\Ticket\Application\Search;

use App\Shared\Application\Input\PayloadMapper;
use App\Shared\Application\Query\SearchQuery;

class SearchTicketQuery extends SearchQuery
{
    public function __construct(
        private ?string $event,
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
            $payload->nullableString('event'),
            $payload->nullableInt('status'),
            $payload->string('orderBy'),
            $payload->string('order'),
            $payload->nullableInt('limit'),
            $payload->nullableInt('page'),
        );
    }

    public function filters(string $companyId): array
    {
        return [
            'event' => $this->event,
            'company' => $companyId,
            'status' => $this->status,
        ];
    }
}