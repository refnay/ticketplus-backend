<?php

namespace App\Tests\Account\Company\Application\Create;

use App\Account\Company\Application\Create\CreateCompanyCommand;
use PHPUnit\Framework\TestCase;

final class CreateCompanyCommandTest extends TestCase
{
    public function testItMapsTimezoneAndDefault(): void
    {
        $command = CreateCompanyCommand::create([
            'country' => 'PE',
            'city' => 'LIM',
            'documentType' => 1,
            'documentNumber' => '20123456789',
            'email' => 'contacto@ticketplus.test',
            'name' => 'Ticket Plus',
            'timezone' => 'America/Lima',
            'default' => ['currency' => 'PEN', 'taxRate' => 18.0],
        ]);

        self::assertSame('America/Lima', $command->timezone());
        self::assertSame(
            ['currency' => 'PEN', 'taxRate' => 18.0],
            $command->default(),
        );
    }
}
