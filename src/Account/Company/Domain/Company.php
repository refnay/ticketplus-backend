<?php

namespace App\Account\Company\Domain;

class Company
{
    private CompanyId $id;
    private CompanyCity $city;
    private CompanyCountry $country;
    private CompanyDocument $document;
    private CompanyEmail $email;
    private CompanyName $name;
    private CompanyStatus $status;
    private CompanyTimezone $timezone;
    private CompanyDefault $default;
    private ?CompanyLocation $location = null;
    private ?CompanyLogo $logo = null;
    private ?CompanyDescription $description = null;
    private ?CompanyTelephone $telephone = null;
    private ?CompanyWebSite $webSite = null;

    public function __construct( 
        CompanyId $id,
        CompanyCity $city,
        CompanyCountry $country,
        CompanyDocument $document,
        CompanyEmail $email,
        CompanyName $name,
        CompanyStatus $status,
        CompanyTimezone $timezone,
        CompanyDefault $default,
        CompanyLocation $location,
        CompanyLogo $logo,
        CompanyDescription $description,
        CompanyTelephone $telephone,
        CompanyWebSite $webSite,
    ) {
        $this->id = $id;
        $this->city = $city;
        $this->country = $country;
        $this->document = $document;
        $this->email = $email;
        $this->name = $name;
        $this->status = $status;
        $this->timezone = $timezone;
        $this->default = $default;
        $this->location = $location;
        $this->logo = $logo;
        $this->description = $description;
        $this->telephone = $telephone;
        $this->webSite = $webSite;
    }

    public static function create(
        CompanyCity $city,
        CompanyCountry $country,
        CompanyDocument $document,
        CompanyEmail $email,
        CompanyName $name,
        CompanyTimezone $timezone,
        CompanyDefault $default,
        CompanyLocation $location,
        CompanyDescription $description,
        CompanyTelephone $telephone,
        CompanyWebSite $webSite,
    ): self {
        return new self(
            CompanyId::generate(),
            $city,
            $country,
            $document,
            $email,
            $name,
            CompanyStatus::pending(),
            $timezone,
            $default,
            $location,
            CompanyLogo::fromNull(),
            $description,
            $telephone,
            $webSite,
        );
    }

    public function id(): CompanyId
    {
        return $this->id;
    }

    public function city(): CompanyCity
    {
        return $this->city;
    }

    public function country(): CompanyCountry
    {
        return $this->country;
    }

    public function document(): CompanyDocument
    {
        return $this->document;
    }

    public function email(): CompanyEmail
    {
        return $this->email;
    }

    public function name(): CompanyName
    {
        return $this->name;
    }

    public function status(): CompanyStatus
    {
        return $this->status;
    }

    public function timezone(): CompanyTimezone
    {
        return $this->timezone;
    }

    public function default(): CompanyDefault
    {
        return $this->default;
    }

    public function location(): CompanyLocation
    {
        return $this->location ?? CompanyLocation::fromNull();
    }

    public function logo(): CompanyLogo
    {
        return $this->logo ?? CompanyLogo::fromNull();
    }

    public function description(): CompanyDescription
    {
        return $this->description ?? CompanyDescription::fromNull();
    }

    public function telephone(): CompanyTelephone
    {
        return $this->telephone ?? CompanyTelephone::fromNull();
    }

    public function webSite(): CompanyWebSite
    {
        return $this->webSite ?? CompanyWebSite::fromNull();
    }

    public function changeCity(CompanyCity $city): void
    {
        $this->city = $city;
    }

    public function changeCountry(CompanyCountry $country): void
    {
        $this->country = $country;
    }

    public function changeDocument(CompanyDocument $document): void
    {
        $this->document = $document;
    }

    public function changeEmail(CompanyEmail $email): void
    {
        $this->email = $email;
    }

    public function changeName(CompanyName $name): void
    {
        $this->name = $name;
    }

    public function changeStatus(CompanyStatus $status): void
    {
        $this->status = $status;
    }

    public function changeTimezone(CompanyTimezone $timezone): void
    {
        $this->timezone = $timezone;
    }

    public function changeDefault(CompanyDefault $default): void
    {
        $this->default = $default;
    }

    public function changeLocation(CompanyLocation $location): void
    {
        $this->location = $location;
    }

    public function changeLogo(CompanyLogo $logo): void
    {
        $this->logo = $logo;
    }

    public function changeDescription(CompanyDescription $description): void
    {
        $this->description = $description;
    }

    public function changeTelephone(CompanyTelephone $telephone): void
    {
        $this->telephone = $telephone;
    }

    public function changeWebSite(CompanyWebSite $webSite): void
    {
        $this->webSite = $webSite;
    }
}
