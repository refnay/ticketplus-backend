<?php

namespace App\Identity\User\Domain;

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
    private ?UserMobile $mobile = null;
    private UserName $name;
    private ?UserProfileImage $profileImage = null;
    private UserStatus $status;
    private UserType $type;

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
}
