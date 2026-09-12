<?php

namespace App\Account\User\Application\SwitchCompany;

use App\Shared\Application\Input\PayloadMapper;

class SwitchUserCompanyCommand
{
    public function __construct(private string $company)
    {
    }


    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self($payload->string('company'));
    }

    public function company(): string
    {
        return $this->company;
    }
}