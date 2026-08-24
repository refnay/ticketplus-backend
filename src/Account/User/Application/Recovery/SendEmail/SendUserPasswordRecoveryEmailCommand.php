<?php

namespace App\Account\User\Application\Recovery\SendEmail;

use App\Shared\Application\Input\PayloadMapper;

class SendUserPasswordRecoveryEmailCommand
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