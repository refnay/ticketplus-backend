<?php

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\DivisionByZeroAttempted;
use App\Shared\Domain\Exceptions\ValueObjectUsedWhileNull;

abstract class IntValueObject
{
    public function __construct(protected ?int $value)
    {
    }

    public static function fromInt(?int $value): static
    {
        return new static($value);
    }

    public static function fromNull(): static
    {
        return new static(null);
    }

    public static function fromZero(): static
    {
        return new static(0);
    }

    public function value(): ?int
    {
        return $this->value;
    }

    public function isNull(): bool
    {
        return is_null($this->value);
    }

    public function isZero(): bool
    {
        return $this->value === 0;
    }

    public function equals(self $otherValue): bool
    {
        return $this->value === $otherValue->value();
    }

    public function greaterThan(self $otherValue): bool
    {
        $this->ensureNotNull();
        $otherValue->ensureNotNull();

        return $this->value > $otherValue->value();
    }

    public function lessThan(self $otherValue): bool
    {
        $this->ensureNotNull();
        $otherValue->ensureNotNull();

        return $this->value < $otherValue->value();
    }

    public function greaterThanOrEqual(self $otherValue): bool
    {
        $this->ensureNotNull();
        $otherValue->ensureNotNull();

        return $this->value >= $otherValue->value();
    }

    public function lessThanOrEqual(self $otherValue): bool
    {
        $this->ensureNotNull();
        $otherValue->ensureNotNull();

        return $this->value <= $otherValue->value();
    }

    public function add(self $otherValue): static
    {
        $this->ensureNotNull();
        $otherValue->ensureNotNull();

        return new static($this->value + $otherValue->value());
    }

    public function subtract(self $otherValue): static
    {
        $this->ensureNotNull();
        $otherValue->ensureNotNull();

        return new static($this->value - $otherValue->value());
    }

    public function multiply(self $otherValue): static
    {
        $this->ensureNotNull();
        $otherValue->ensureNotNull();

        return new static($this->value * $otherValue->value());
    }

    public function divide(self $otherValue): static
    {
        $this->ensureNotNull();
        $otherValue->ensureNotNull();
        $otherValue->ensureNotZero();

        return new static(intdiv($this->value, $otherValue->value()));
    }

    private function ensureNotNull(): void
    {
        if ($this->isNull()) {
            throw new ValueObjectUsedWhileNull();
        }
    }

    private function ensureNotZero(): void
    {
        if ($this->isZero()) {
            throw new DivisionByZeroAttempted();
        }
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    abstract function validate(): void;
}