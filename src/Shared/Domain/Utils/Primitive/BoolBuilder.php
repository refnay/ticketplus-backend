<?php

namespace App\Shared\Domain\Utils\Primitive;

class BoolBuilder
{
    public function __construct(private bool $value)
    {
    }

    public static function enable(): self
    {
        return new self(true);
    }

    public static function disable(): self
    {
        return new self(false);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value();
    }

    public function enabled(): void
    {
        $this->value = true;
    }

    public function disabled(): void
    {
        $this->value = false;
    }

    public static function fromBool(bool $value): self
    {
        return new self($value);
    }

    public function value(): bool
    {
        return $this->value;
    }
}
