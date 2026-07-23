<?php

namespace App\Identity\User\Application\Recovery\SendEmail;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;

class SendUserPasswordRecoveryEmailCommand extends BaseCommand
{
    public function __construct(private string $email)
    {
    }

    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self($payload->string('email'));
    }

    public function email(): string
    {
        return $this->email;
    }
}