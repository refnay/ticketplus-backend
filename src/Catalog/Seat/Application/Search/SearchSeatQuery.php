<?php

namespace App\Catalog\Seat\Application\Search;

use App\Shared\Application\Query\SearchQuery;
use App\Shared\Application\Input\PayloadMapper;

class SearchSeatQuery extends SearchQuery
{
    public function __construct(
        private string $event,
        private string $day,
        private string $zone,
        private ?string $code,
        private ?bool $numberedSeating,
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
            $payload->nullableBoolFromString('numberedSeating'),
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
    
    public function numberedSeating(): string
    {
        return $this->numberedSeating;
    }

    public function filters(): array
    {
        $filters = get_object_vars($this);

        return $filters; 
    }
}