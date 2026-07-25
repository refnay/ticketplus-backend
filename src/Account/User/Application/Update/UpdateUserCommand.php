<?php

namespace App\Account\User\Application\Update;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;

class UpdateUserCommand extends BaseCommand
{
    public function __construct(
        private string $name,
        private string $lastName,
        private string $birthDate,
        private string $city,
        private string $country,
        private ?string $mobile,
        private int $documentType,
        private string $documentNumber,
    ) {
    }
    
    
    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $payload->string('name'),
            $payload->string('lastName'),
            $payload->string('birthDate'),
            $payload->string('city'),
            $payload->string('country'),
            $payload->nullableString('mobile'),
            $payload->int('documentType'),
            $payload->string('documentNumber'),
        );
    }

    public function birthDate(): string
    {
        return $this->birthDate;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function country(): string
    {
        return $this->country;
    }

    public function documentType(): int
    {
        return $this->documentType;
    }

    public function documentNumber(): string
    {
        return $this->documentNumber;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function mobile(): ?string
    {
        return $this->mobile;
    }
}