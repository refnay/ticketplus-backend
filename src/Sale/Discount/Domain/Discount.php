<?php

namespace App\Sale\Discount\Domain;

use App\Sale\Event\Domain\Event;

class Discount
{
    private DiscountId $id;
    private DiscountActive $active;
    private DiscountCode $code;
    private DiscountStartDate $startDate;
    private DiscountEndDate $endDate;
    private DiscountType $type;
    private DiscountUsage $usage;
    private DiscountValue $value;
    private Event $event;

    public function __construct(
        DiscountId $id,
        DiscountActive $active,
        DiscountCode $code,
        DiscountStartDate $startDate,
        DiscountEndDate $endDate,
        DiscountType $type,
        DiscountUsage $usage,
        DiscountValue $value,
    ) {
        $this->id = $id;
        $this->active = $active;
        $this->code = $code;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->type = $type;
        $this->usage = $usage;
        $this->value = $value;
    }

    public static function create(
        DiscountActive $active,
        DiscountCode $code,
        DiscountStartDate $startDate,
        DiscountEndDate $endDate,
        DiscountType $type,
        DiscountUsage $usage,
        DiscountValue $value,
    ): self {
        return new self(
            DiscountId::generate(),
            $active,
            $code,
            $startDate,
            $endDate,
            $type,
            $usage,
            $value,
        );
    }

    public function id(): DiscountId
    {
        return $this->id;
    }

    public function active(): DiscountActive
    {
        return $this->active;
    }

    public function code(): DiscountCode
    {
        return $this->code;
    }

    public function startDate(): DiscountStartDate
    {
        return $this->startDate;
    }

    public function endDate(): DiscountEndDate
    {
        return $this->endDate;
    }

    public function type(): DiscountType
    {
        return $this->type;
    }

    public function usage(): DiscountUsage
    {
        return $this->usage;
    }

    public function value(): DiscountValue
    {
        return $this->value;
    }

    public function event(): Event
    {
        return $this->event;
    }

    public function changeActive(DiscountActive $active): void
    {
        $this->active = $active;
    }

    public function changeCode(DiscountCode $code): void
    {
        $this->code = $code;
    }

    public function changeStartDate(DiscountStartDate $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function changeEndDate(DiscountEndDate $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function changeType(DiscountType $type): void
    {
        $this->type = $type;
    }

    public function changeUsage(DiscountUsage $usage): void
    {
        $this->usage = $usage;
    }

    public function changeValue(DiscountValue $value): void
    {
        $this->value = $value;
    }

    public function changeEvent(Event $event): void
    {
        $this->event = $event;
    }
}
