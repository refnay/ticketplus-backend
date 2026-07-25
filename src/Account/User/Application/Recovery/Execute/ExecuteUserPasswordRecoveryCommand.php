<?php

namespace App\Account\User\Application\Recovery\Execute;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;

class ExecuteUserPasswordRecoveryCommand extends BaseCommand
{
    public function __construct(private string $token, private string $newPassword)
    {
    }

    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self($payload->string('token'), $payload->string('newPassword'));
    }

    public function token(): string
    {
        return $this->token;
    }

    public function newPassword(): string
    {
        return $this->newPassword;
    }
}