<?php

namespace App\Catalog\Event\Application\ViewPublished;

use App\Shared\Application\Input\PayloadMapper;

class ViewPublishedEventQuery
{
    public function __construct(private string $id)
    {
    }

    public static function create(string $id): self
    {
        return new self(PayloadMapper::fromData(['id' => $id])->string('id'));
    }

    public function id(): string
    {
        return $this->id;
    }
}
