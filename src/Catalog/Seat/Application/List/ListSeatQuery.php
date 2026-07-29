<?php

namespace App\Catalog\Seat\Application\List;

use App\Shared\Application\Query\ListQuery;
use App\Shared\Domain\Utils\PayloadMapper;

class ListSeatQuery extends ListQuery
{
    public function __construct(
        private string $event,
        private string $day,
        private string $zone,
        private ?string $code,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $page,
    ) {
        parent::__construct($orderBy, $order, $limit, $page);
    }

    public static function fromQuery(string $event, string $day, string $zone, array $data): self
    {
        $payload = PayloadMapper::fromData($data);
        
        return new self(
            $event,
            $day,
            $zone,
            $payload->nullableString('code'),
            $payload->string('orderBy'),
            $payload->string('order'),
            $payload->nullableInt('limit'),
            $payload->nullableInt('page') ,
        );
    }

    public function event(): string
    {
        return $this->event;
    }

    public function day(): string
    {
        return $this->day;
    }

    public function zone(): string
    {
        return $this->zone;
    }

    public function filters(): array
    {
        $filters = get_object_vars($this);

        return $filters; 
    }
}