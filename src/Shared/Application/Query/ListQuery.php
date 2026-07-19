<?php

namespace App\Shared\Application\Query;

class ListQuery extends BaseQuery
{
    public function __construct(
        private string $orderBy = 'createdAt',
        private string $order = 'DESC',
        private ?int $limit = 10,
        private ?int $page = 1,
    ) {
    }

    public function orderBy(): string
    {
        return $this->orderBy;
    }

    public function order(): string
    {
        return $this->order;
    }

    public function limit(): ?int
    {
        return $this->limit;
    }

    public function page(): ?int
    {
        return $this->page;
    }

    public function offset(): ?int
    {
        if (is_null($this->page) || is_null($this->limit)) {
            return null;
        }
        
        return ($this->page - 1) * $this->limit;
    }
}