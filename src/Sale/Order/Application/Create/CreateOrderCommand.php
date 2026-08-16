<?php

namespace App\Sale\Order\Application\Create;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;
use App\Shared\Domain\Utils\Primitive\ArrayBuilder;

class CreateOrderCommand extends BaseCommand
{
    public function __construct(
        private string $event,
        private string $day,
        private ?string $discount,
        private array $zones,
    ) {
    }

    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);
        $zones = ArrayBuilder::generate();

        foreach ($payload->array('zones') as $zone) {
            $zones->add(ZoneCommand::create($zone));
        }

        return new self(
            $payload->string('event'),
            $payload->string('day'),
            $payload->nullableString('discount'),
            $zones->items(),
        );
    }

    public function event(): string
    {
        return $this->event;
    }

    public function day(): string
    {
        return $this->day;
    }

    public function discount(): ?string
    {
        return $this->discount;
    }

    public function zones(): array
    {
        return  $this->zones;
    }
}
