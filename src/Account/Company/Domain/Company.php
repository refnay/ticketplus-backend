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
    private ?CompanyLocation $location = null;
    private ?CompanyLogo $logo = null;
    private ?CompanyDescription $description = null;
    private ?CompanyTelephone $telephone = null;
    private ?CompanyWebSite $webSite = null;
    /** @var CompanyMember[] $members */
    private $members = [];

    public function __construct( 
        CompanyId $id,
        CompanyCity $city,
        CompanyCountry $country,
        CompanyDocument $document,
        CompanyEmail $email,
        CompanyName $name,
        CompanyStatus $status,
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
            $location,
            CompanyLogo::fromEmpty(),
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
    
    /** @return CompanyMember[] */
    public function members()
    {
        return $this->members;
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

    public function addMember(CompanyMember $member): void
    {
        $this->members[] = $member;
    }

    public function findMemberById(CompanyMemberId $id): ?CompanyMember
    {
        foreach ($this->members() as $member) {
            if ($member->id()->equals($id)) {
                return $member;
            }
        }

        return null;
    }

    public function removeMemberById(CompanyMemberId $id): bool
    {
        foreach ($this->members() as $index => $member) {
            if ($member->id()->equals($id)) {
                unset($this->members[$index]);
                
                return true;
            }
        }

        return false;
    }
}
