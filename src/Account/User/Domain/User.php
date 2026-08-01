<?php

namespace App\Account\User\Domain;

use App\Account\Company\Domain\Company;
use App\Account\CompanyMember\Domain\CompanyMember;
use App\Account\CompanyMember\Domain\CompanyMemberId;

class User
{
    private UserId $id;
    private UserEmail $email;
    private UserPassword $password;
    private UserBirthDate $birthDate;
    private UserCity $city;
    private UserCountry $country;
    private UserDocument $document;
    private UserLastName $lastName;
    private UserName $name;
    private UserStatus $status;
    private UserType $type;
    private UserOwner $owner;
    private ?UserMobile $mobile = null;
    private ?UserProfileImage $profileImage = null;
    /** @var CompanyMember[] $companies */
    private $companies = [];

    public function __construct(
        UserId $id,
        UserEmail $email,
        UserPassword $password,
        UserBirthDate $birthDate,
        UserCity $city,
        UserCountry $country,
        UserDocument $document,
        UserLastName $lastName,
        UserMobile $mobile,
        UserName $name,
        UserProfileImage $profileImage,
        UserStatus $status,
        UserType $type,
        UserOwner $owner
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->birthDate = $birthDate;
        $this->city = $city;
        $this->country = $country;
        $this->document = $document;
        $this->lastName = $lastName;
        $this->mobile = $mobile;
        $this->name = $name;
        $this->profileImage = $profileImage;
        $this->status = $status;
        $this->type = $type;
        $this->owner = $owner;
    }

    public static function create(
        UserEmail $email,
        UserPassword $password,
        UserBirthDate $birthDate,
        UserCity $city,
        UserCountry $country,
        UserDocument $document,
        UserLastName $lastName,
        UserMobile $mobile,
        UserName $name,
    ): self {
        return new self(
            UserId::generate(),
            $email,
            $password,
            $birthDate,
            $city,
            $country,
            $document,
            $lastName,
            $mobile,
            $name,
            UserProfileImage::fromNull(),
            UserStatus::pending(),
            UserType::simple(),
            UserOwner::disable(),
        );
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function password(): UserPassword
    {
        return $this->password;
    }

    public function birthDate(): UserBirthDate
    {
        return $this->birthDate;
    }

    public function city(): UserCity
    {
        return $this->city;
    }

    public function country(): UserCountry
    {
        return $this->country;
    }

    public function document(): UserDocument
    {
        return $this->document;
    }

    public function lastName(): UserLastName
    {
        return $this->lastName;
    }

    public function mobile(): UserMobile
    {
        return $this->mobile ?? UserMobile::fromNull();
    }

    public function name(): UserName
    {
        return $this->name;
    }

    public function owner(): UserOwner
    {
        return $this->owner;
    }

    public function profileImage(): UserProfileImage
    {
        return $this->profileImage ?? UserProfileImage::fromNull();
    }

    public function status(): UserStatus
    {
        return $this->status;
    }

    public function type(): UserType
    {
        return $this->type;
    }
    
    /** @return CompanyMember[] */
    public function companies()
    {
        return $this->companies;
    }

    public function  changeEmail(UserEmail $email): void
    {
        $this->email = $email;
    }

    public function  changePassword(UserPassword $password): void
    {
        $this->password = $password;
    }

    public function  changeBirthDate(UserBirthDate $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function  changeCity(UserCity $city): void
    {
        $this->city = $city;
    }

    public function  changeCountry(UserCountry $country): void
    {
        $this->country = $country;
    }

    public function  changeDocument(UserDocument $document): void
    {
        $this->document = $document;
    }

    public function  changeLastName(UserLastName $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function  changeMobile(UserMobile $mobile): void
    {
        $this->mobile = $mobile;
    }

    public function  changeName(UserName $name): void
    {
        $this->name = $name;
    }

    public function  changeProfileImage(UserProfileImage $profileImage): void
    {
        $this->profileImage = $profileImage;
    }

    public function  changeStatus(UserStatus $status): void
    {
        $this->status = $status;
    }

    public function  changeType(UserType $type): void
    {
        $this->type = $type;
    }

    public function changeOwner(UserOwner $owner): void
    {
        $this->owner = $owner;
    }
    
    public function addCompany(CompanyMember $company): void
    {
        $this->companies[] = $company;
    }

    public function findCompanyById(CompanyMemberId $id): ?CompanyMember
    {
        foreach ($this->companies() as $company) {
            if ($company->id()->equals($id)) {
                return $company;
            }
        }

        return null;
    }

    public function removeCompanyById(CompanyMemberId $id): bool
    {
        foreach ($this->companies() as $index => $company) {
            if ($company->id()->equals($id)) {
                unset($this->company[$index]);
                
                return true;
            }
        }

        return false;
    }
    
    public function currentCompany(): ?Company
    {
        foreach ($this->companies() as $company) {
            if ($company->current()->isEnable()) {
                return $company->company();
            }
        }

        return null;
    }
}