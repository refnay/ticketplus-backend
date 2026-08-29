<?php

namespace App\Account\Company\Application\Find;

use App\Account\Company\Domain\Company;
use JsonSerializable;
use Override;

final class CompanyResponse implements JsonSerializable
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $timezone,
        private readonly array $default,
        private readonly ?string $description,
        private readonly ?string $logo,
        private readonly string $email,
        private readonly ?string $telephone,
        private readonly ?string $webSite,
        private readonly string $country,
        private readonly string $city,
        private readonly array $document,
        private readonly ?string $location,
        private readonly int $status,
    ) {
    }

    public static function create(Company $company): self
    {
        return new self(
            $company->id()->value(),
            $company->name()->value(),
            $company->timezone()->value(),
            $company->default()->value(),
            $company->description()->value(),
            $company->logo()->value(),
            $company->email()->value(),
            $company->telephone()->value(),
            $company->webSite()->value(),
            $company->country()->value(),
            $company->city()->value(),
            $company->document()->toArray(),
            $company->location()->value(),
            $company->status()->value(),
        );
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
