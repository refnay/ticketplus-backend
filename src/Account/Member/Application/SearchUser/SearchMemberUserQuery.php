<?php

namespace App\Account\Member\Application\SearchUser;

use App\Shared\Application\Input\PayloadMapper;
use App\Shared\Application\Query\SearchQuery;

class SearchMemberUserQuery extends SearchQuery
{
    public function __construct(
        private ?string $value,
        private ?int $role,
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
            $payload->nullableString('value'),
            $payload->nullableInt('role'),
            $payload->nullableInt('status'),
            $payload->nullableString('orderBy') ?? 'createdAt',
            $payload->nullableString('order') ?? 'DESC',
            $payload->nullableInt('limit'),
            $payload->nullableInt('page'),
        );
    }

    public function filters(string $companyId): array
    {
        $filters = get_object_vars($this);
        $filters['company'] = $companyId;

        return $filters;
    }
}
