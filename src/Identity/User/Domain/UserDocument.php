<?php

namespace App\Identity\User\Domain;

class UserDocument
{
    public function __construct(private string $type, private string $number)
    {
    }

    public static function create(string $type, string $number): self
    {
        return new self($type, $number);
    }

    public static function fromData(array $data): self
    {
        return new self($data['type'], $data['number']);
    }

    public function type(): string
    {
        return $this->type;
    }

    public function number(): string
    {
        return $this->number;
    }
}
