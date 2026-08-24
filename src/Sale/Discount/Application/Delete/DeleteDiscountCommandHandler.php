<?php

namespace App\Sale\Discount\Application\Delete;

use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Shared\Domain\CompanyId;
use App\Sale\Event\Domain\EventId;

class DeleteDiscountCommandHandler
{
    public function __construct(private DiscountDeleter $deleter)
    {
    }

    public function __invoke(DeleteDiscountCommand $command): void
    {
        $this->deleter->__invoke(
            DiscountId::fromString($command->id()),
            EventId::fromString($command->event()),
            CompanyId::fromString($command->session()->company()),
        );
    }
}
