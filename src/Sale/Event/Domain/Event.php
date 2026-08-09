<?php

namespace App\Sale\Event\Domain;

use App\Sale\Purchase\Domain\CompanyId;

class Event
{
    private EventId $id;
    private EventName $name;
    private EventStatus $status;
    private CompanyId $companyId;

    public function __construct(
        EventId $id,
        EventName $name,
        EventStatus $status,
        CompanyId $companyId,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->companyId = $companyId;
    }

    public function id(): EventId
    {
        return $this->id;
    }

    public function name(): EventName
    {
        return $this->name;
    }

    public function status(): EventStatus
    {
        return $this->status;
    }

    public function companyId(): CompanyId
    {
        return $this->companyId;
    }
}
