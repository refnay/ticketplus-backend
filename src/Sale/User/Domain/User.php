<?php

namespace App\Sale\User\Domain;

class User
{
    private string $id;
    private string $name;
    private string $lastName;
    private string $email;

    public function __construct(string $id, string $name, string $lastName, string $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    public static function create(string $id, string $name, string $lastName, string $email): self
    {
        return new self($id, $name, $lastName, $email);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function email(): string
    {
        return $this->email;
    }
}
