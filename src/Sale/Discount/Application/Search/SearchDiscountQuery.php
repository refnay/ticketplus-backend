<?php

namespace App\Sale\Discount\Application\Search;

use App\Shared\Application\Query\SearchQuery;
use App\Shared\Application\Input\PayloadMapper;

class SearchDiscountQuery extends SearchQuery
{
    public function __construct(
        private ?string $event,
        private ?string $code,
        private ?int $type,
        private ?bool $active,
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
            $payload->nullableString('code'),
            $payload->nullableInt('type'),
            $payload->nullableBool('active'),
            $payload->string('orderBy'),
            $payload->string('order'),
            $payload->nullableInt('limit'),
            $payload->nullableInt('page'),
        );
    }

    public function event(): ?string
    {
        return $this->event;
    }

    public function filters(string $companyId): array
    {
        return [
            'event' => $this->event,
            'company' => $companyId,
            'code' => $this->code,
            'type' => $this->type,
            'active' => $this->active,
        ];
    }
}
