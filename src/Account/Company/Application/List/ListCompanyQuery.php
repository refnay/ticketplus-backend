<?php

namespace App\Account\Company\Application\List;

use App\Shared\Application\Query\ListQuery;
use App\Shared\Domain\Utils\PayloadMapper;

class ListCompanyQuery extends ListQuery
{
    public function __construct(
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