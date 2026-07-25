<?php

namespace App\Account\User\Application\UpdatePassword;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;

class UpdateUserPasswordCommand extends BaseCommand
{
    public function __construct(private string $oldPassword, private string $newPassword)
    {
    }

    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self($payload->string('oldPassword'), $payload->string('newPassword'));
    }

    public function oldPassword(): string
    {
        return $this->oldPassword;
    }

    public function newPassword(): string
    {
        return $this->newPassword;
    }
}