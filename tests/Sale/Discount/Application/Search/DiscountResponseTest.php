<?php

namespace App\Tests\Sale\Discount\Application\Search;

use App\Sale\Discount\Application\Search\DiscountResponse;
use App\Sale\Discount\Domain\Discount;
use App\Sale\Discount\Domain\DiscountActive;
use App\Sale\Discount\Domain\DiscountCode;
use App\Sale\Discount\Domain\DiscountEndDate;
use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Discount\Domain\DiscountStartDate;
use App\Sale\Discount\Domain\DiscountType;
use App\Sale\Discount\Domain\DiscountUsage;
use App\Sale\Discount\Domain\DiscountValue;
use App\Sale\Reference\Event\Domain\Event;
use App\Sale\Reference\Event\Domain\EventId;
use PHPUnit\Framework\TestCase;

final class DiscountResponseTest extends TestCase
{
    public function testItIncludesTheReferencedEventName(): void
    {
        $eventId = '018f7c54-5f88-7e04-8a90-7af68c932551';
        $discount = new Discount(
            DiscountId::fromString('018f7c54-5f88-7e04-8a90-7af68c932550'),
            DiscountActive::fromBool(true),
            DiscountCode::fromString('SAVE5'),
            DiscountStartDate::fromString('2026-08-01'),
            DiscountEndDate::fromString('2026-08-31'),
            DiscountType::fromInt(1),
            DiscountUsage::create(100, 10),
            DiscountValue::fromFloat(5.0),
            EventId::fromString($eventId),
        );
        $event = Event::create($eventId, 'PEN', 'Festival de Verano', 18.0);

        self::assertSame(
            'Festival de Verano',
            DiscountResponse::create($discount, $event)->jsonSerialize()['eventName'],
        );
    }
}
