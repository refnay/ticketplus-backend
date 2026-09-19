<?php

namespace App\Catalog\Event\Application\Choose;

use App\Shared\Application\Query\SearchQuery;

class ChooseEventsQuery extends SearchQuery
{
    public static function create(): self
    {
        return new self('name', 'ASC', null, null);
    }

    public function filters(string $companyId): array
    {
        $filters = get_object_vars($this);
        $filters['company'] = $companyId;

        return $filters;
    }
}
