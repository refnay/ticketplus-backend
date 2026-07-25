<?php

namespace App\Account\User\Application\Create;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;

class CreateUserCommand extends BaseCommand
{
    public function __construct(
        private string $email,
        private string $password,
        private string $birthDate,
        private string $city,
        private string $country,
        private int $documentType,
        private string $documentNumber,
        private string $name,
        private string $lastName,
        private ?string $mobile,
    ) {
    }

    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $payload->string('email'),
            $payload->string('password'),
            $payload->string('birthDate'),
            $payload->string('city'),
            $payload->string('country'),
            $payload->int('documentType'),
            $payload->string('documentNumber'),
            $payload->string('name'),
            $payload->string('lastName'),
            $payload->nullableString('mobile'),
        );
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
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