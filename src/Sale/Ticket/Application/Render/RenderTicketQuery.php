<?php

namespace App\Sale\Ticket\Application\Render;

use App\Shared\Application\Query\BaseQuery;

class RenderTicketQuery extends BaseQuery
{
    public function __construct(private string $id, private string $order)
    {
    }

    public static function create(string $id, string $order): self
    {
        return new self($id, $order);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function order(): string
    {
        return $this->order;
    }
}
