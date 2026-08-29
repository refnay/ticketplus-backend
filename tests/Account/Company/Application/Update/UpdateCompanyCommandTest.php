<?php

namespace App\Tests\Account\Company\Application\Update;

use App\Account\Company\Application\Update\UpdateCompanyCommand;
use PHPUnit\Framework\TestCase;

final class UpdateCompanyCommandTest extends TestCase
{
    public function testItMapsTimezoneAndDefault(): void
    {
        $command = UpdateCompanyCommand::create([
            'country' => 'PE',
            'city' => 'LIM',
            'documentType' => 1,
            'documentNumber' => '20123456789',
            'email' => 'contacto@ticketplus.test',
            'name' => 'Ticket Plus',
            'timezone' => 'America/Bogota',
            'default' => ['currency' => 'USD', 'taxRate' => 10.0],
        ]);

        self::assertSame('America/Bogota', $command->timezone());
        self::assertSame(
            ['currency' => 'USD', 'taxRate' => 10.0],
            $command->default(),
        );
    }
}
