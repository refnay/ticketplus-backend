<?php

namespace App\Catalog\Event\Application\List;

use App\Shared\Application\Query\ListQuery;
use App\Shared\Domain\Utils\PayloadMapper;

class ListEventQuery extends ListQuery
{
    public function __construct(
        private ?string $name,
        private ?string $country,
        private ?string $city,
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
            $payload->nullableString('name'),
            $payload->nullableString('country'),
            $payload->nullableString('city'),
            $payload->nullableInt('status'),
            $payload->string('orderBy'),
            $payload->string('order'),
            $payload->nullableInt('limit'),
            $payload->nullableInt('page') ,
        );
    }
    
    public function filters(): array
    {
        $filters = get_object_vars($this);

        return $filters; 
    }
}