<?php

namespace App\Account\User\Application\Company\Switch;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;

class SwitchUserCompanyCommand extends BaseCommand
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