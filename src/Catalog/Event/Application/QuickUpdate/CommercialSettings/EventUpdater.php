<?php

namespace App\Catalog\Event\Application\QuickUpdate\CommercialSettings;

use App\Catalog\Event\Domain\EventCurrency;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventOrderLimit;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\EventTaxRate;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Shared\Domain\CompanyId;

class EventUpdater
{
    public function __construct(private EventRepository $repository, private EventFinder $eventFinder) {}

    public function __invoke(
        EventId $id,
        EventCurrency $currency,
        EventTaxRate $taxRate,
        EventOrderLimit $orderLimit,
        CompanyId $companyId,
    ): void {
        $event = $this->eventFinder->__invoke($id, $companyId);

        $event->changeCurrency($currency);
        $event->changeTaxRate($taxRate);
        $event->changeOrderLimit($orderLimit);

        $this->repository->update($event);
    }
}
