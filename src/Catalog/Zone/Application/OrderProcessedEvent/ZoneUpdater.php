<?php

namespace App\Catalog\Zone\Application\OrderProcessedEvent;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Shared\Domain\OrderStatusList;
use App\Catalog\Zone\Domain\Exceptions\ZoneNotUpdated;
use App\Catalog\Zone\Domain\Services\ZoneFinder;
use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Zone\Domain\ZoneRepository;
use App\Shared\Infrastructure\Persistence\Doctrine\TransactionManager;
use Throwable;

class ZoneUpdater
{
    public function __construct(
        private ZoneRepository $repository,
        private ZoneFinder $finder,
        private TransactionManager $transaction,
    ) {}

    public function __invoke(EventDayId $dayId, array $items, int $status): void
    {
        $this->transaction->begin();
        try {
            foreach ($items as $item) {
                $zone = $this->finder->__invoke(ZoneId::fromString($item['zone']), $dayId);
                $reserved = $zone->quantity()->reserved();

                switch ($status) {
                    case OrderStatusList::PENDING->value:
                        $reserved = $zone->quantity()->reserved() + $item['quantity'];
                        break;
                    case OrderStatusList::EXPIRED->value:
                        $reserved = $zone->quantity()->reserved() - $item['quantity'];
                        break;
                    default:
                        continue;
                }

                $zone->changeReservedQuantity($reserved);

                $this->repository->update($zone);
            }
            $this->transaction->commit();
        } catch (Throwable) {
            $this->transaction->rollback();
            throw new ZoneNotUpdated();
        }
    }
}
