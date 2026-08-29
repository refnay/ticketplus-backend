<?php

namespace App\Tests\Account\Company\Application\Find;

use App\Account\Company\Application\Find\CompanyResponse;
use App\Account\Company\Domain\Company;
use App\Account\Company\Domain\CompanyCity;
use App\Account\Company\Domain\CompanyCountry;
use App\Account\Company\Domain\CompanyDescription;
use App\Account\Company\Domain\CompanyDocument;
use App\Account\Company\Domain\CompanyEmail;
use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\CompanyLocation;
use App\Account\Company\Domain\CompanyLogo;
use App\Account\Company\Domain\CompanyName;
use App\Account\Company\Domain\CompanyStatus;
use App\Account\Company\Domain\CompanyTelephone;
use App\Account\Company\Domain\CompanyWebSite;
use PHPUnit\Framework\TestCase;

final class CompanyResponseTest extends TestCase
{
    public function testItSerializesACompany(): void
    {
        $company = new Company(
            CompanyId::fromString('018f7c54-5f88-7e04-8a90-7af68c932551'),
            CompanyCity::fromString('LIM'),
            CompanyCountry::fromString('PE'),
            CompanyDocument::create(1, '20123456789'),
            CompanyEmail::fromString('contacto@ticketplus.test'),
            CompanyName::fromString('Ticket Plus'),
            CompanyStatus::fromInt(1),
            CompanyLocation::fromString('Av. Principal 123'),
            CompanyLogo::fromString('https://images.test/logo.png'),
            CompanyDescription::fromString('Productora de eventos'),
            CompanyTelephone::fromString('+51 999 999 999'),
            CompanyWebSite::fromString('https://ticketplus.test'),
        );

        self::assertSame([
            'id' => '018f7c54-5f88-7e04-8a90-7af68c932551',
            'name' => 'Ticket Plus',
            'description' => 'Productora de eventos',
            'logo' => 'https://images.test/logo.png',
            'email' => 'contacto@ticketplus.test',
            'telephone' => '+51 999 999 999',
            'webSite' => 'https://ticketplus.test',
            'country' => 'PE',
            'city' => 'LIM',
            'document' => ['type' => 1, 'number' => '20123456789'],
            'location' => 'Av. Principal 123',
            'status' => 1,
        ], CompanyResponse::create($company)->jsonSerialize());
    }
}
