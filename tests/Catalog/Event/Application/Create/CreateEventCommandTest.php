<?php

namespace App\Tests\Catalog\Event\Application\Create;

use App\Catalog\Event\Application\Create\CreateEventCommand;
use PHPUnit\Framework\TestCase;

final class CreateEventCommandTest extends TestCase
{
    public function testItMapsVenueAndCoordinates(): void
    {
        $command = CreateEventCommand::create([
            'name' => 'Ticket+ Live',
            'description' => null,
            'venue' => 'Estadio Nacional',
            'coordinates' => [
                'latitude' => -12.0675,
                'longitude' => -77.0336,
            ],
            'location' => 'Calle Jose Diaz s/n',
            'country' => 'PE',
            'city' => 'LIM',
            'currency' => 'PEN',
            'taxRate' => 18,
            'category' => '018f7c54-5f88-7e04-8a90-7af68c932551',
            'days' => [],
        ]);

        self::assertSame('Estadio Nacional', $command->venue());
        self::assertSame([
            'latitude' => -12.0675,
            'longitude' => -77.0336,
        ], $command->coordinates());
    }
}
