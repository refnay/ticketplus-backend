<?php

namespace App\Identity\User\Domain;

class UserDocument
{
    public function __construct(private int $type, private string $number)
    {
    }

    public static function create(int $type, string $number): self
    {
        return new self($type, $number);
    }

    public static function fromData(array $data): self
    {
        return new self($data['type'], $data['number']);
    }

    public function type(): int
    {
        return $this->type;
    }

    public function number(): string
    {
        return $this->number;
    }
}
