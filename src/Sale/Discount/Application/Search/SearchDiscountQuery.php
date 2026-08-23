<?php

namespace App\Sale\Discount\Application\Search;

use App\Shared\Application\Query\ListQuery;
use App\Shared\Domain\Utils\PayloadMapper;

class SearchDiscountQuery extends ListQuery
{
    public function __construct(
        private string $event,
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

    public static function fromQuery(string $event, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $event,
            $payload->nullableString('code'),
            $payload->nullableInt('type'),
            $payload->nullableBool('active'),
            $payload->string('orderBy'),
            $payload->string('order'),
            $payload->nullableInt('limit'),
            $payload->nullableInt('page'),
        );
    }

    public function event(): string
    {
        return $this->event;
    }

    public function filters(): array
    {
        return get_object_vars($this);
    }
}
