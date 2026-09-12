<?php

namespace App\Sale\Reference\Seat\Domain;

class Seat
{
    private string $id;
    private string $code;
    private int $status;

    public function __construct(string $id, string $code, int $status, private string $zoneId)
    {
        $this->id = $id;
        $this->code = $code;
        $this->status = $status;
    }

    public static function create(string $id, string $code, int $status, string $zoneId): self
    {
        return new self($id, $code, $status, $zoneId);
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

    public function zoneId(): string
    {
        return $this->zoneId;
    }
}
