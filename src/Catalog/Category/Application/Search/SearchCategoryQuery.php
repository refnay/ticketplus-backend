<?php

namespace App\Catalog\Category\Application\Search;

use App\Shared\Application\Query\ListQuery;
use App\Shared\Domain\Utils\PayloadMapper;

class SearchCategoryQuery extends ListQuery
{
    public function __construct(
        private ?string $name,
        private ?int $reference,
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
            $payload->nullableInt('reference'),
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