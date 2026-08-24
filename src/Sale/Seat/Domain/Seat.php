<?php

namespace App\Sale\Seat\Domain;

class Seat
{
    private string $id;
    private string $code;
    private int $status;

    public function __construct(string $id, string $code, int $status)
    {
        $this->id = $id;
        $this->code = $code;
        $this->status = $status;
    }

    public static function create(string $id, string $code, int $status): self
    {
        return new self($id, $code, $status);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function status(): int
    {
        return $this->status;
    }
}
