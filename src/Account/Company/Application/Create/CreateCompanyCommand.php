<?php

namespace App\Account\Company\Application\Create;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;

class CreateCompanyCommand extends BaseCommand
{
    public function __construct(
        private string $country,
        private string $city,
        private int $documentType,
        private string $documentNumber,
        private string $email,
        private string $name,
        private ?string $location,
        private ?string $description,
        private ?string $telephone,
        private ?string $webSite,
    ) {
    }

    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $payload->string('country'),
            $payload->string('city'),
            $payload->int('documentType'),
            $payload->string('documentNumber'),
            $payload->string('email'),
            $payload->string('name'),
            $payload->nullableString('location'),
            $payload->nullableString('description'),
            $payload->nullableString('telephone'),
            $payload->nullableString('webSite'),
        );
    }

    public function country(): string
    {
        return $this->country;
    }

    public function city(): string
    {
        return $this->city;
    }
        public function documentType(): int
    {
        return $this->documentType;
    }

    public function documentNumber(): string
    {
        return $this->documentNumber;
    }
    
    public function email(): string
    {
        return $this->email;
    }

    public function name(): string
    {
        return $this->name;
    }
    
    public function location(): ?string
    {
        return $this->location;
    }

    public function description(): ?string
    {
        return $this->description;
    }
    
    public function telephone(): ?string
    {
        return $this->telephone;
    }

    public function webSite(): ?string
    {
        return $this->webSite;
    }
}