<?php

namespace App\Sale\Ticket\Domain;

use App\Shared\Domain\ValueObjects\StringValueObject;
use DateTimeImmutable;
use Override;

class TicketCode extends StringValueObject
{
    private const string CHARACTERS = 'ABCDEFGHJKLMNPQRSTUVWXYZ2346789';
    private const string DATE_FORMAT = 'ymd';

    #[Override]
    public function validate(): void {}

    public static function generate(): self
    {
        $date = new DateTimeImmutable();

        $random = '';

        for ($i = 0; $i < 6; $i++) {
            $random .= self::CHARACTERS[random_int(0, strlen(self::CHARACTERS) - 1)];
        }

        return new self($date->format(self::DATE_FORMAT) . $random);
    }
}
