<?php

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\DivisionByZeroAttempted;
use App\Shared\Domain\Exceptions\ValueObjectUsedWhileNull;

abstract class FloatValueObject
{
    public function __construct(protected ?float $value)
    {
    }

    public static function fromFloat(?float $value): static
    {
        return new static($value);
    }

    public static function fromZero(): static
    {
        return new static(0.00);
    }

    public static function fromNull(): static
    {
        return new static(null);
    }

    public function value(): ?float
    {
        return $this->value;
    }

    public function isNull(): bool
    {
        return is_null($this->value);
    }

    public function isZero(): bool
    {
        return $this->value === 0.00;
    }

    public function decimal(): float
    {
        $this->ensureNotNull();

        return $this->value / 100.00;
    }

    public function equals(self $other, float $epsilon = 0.00001): bool
    {
        $this->ensureNotNull();
        $other->ensureNotNull();

        return abs($this->value - $other->value()) < $epsilon;
    }

    public function greaterThan(self $other): bool
    {
        $this->ensureNotNull();
        $other->ensureNotNull();

        return $this->value > $other->value();
    }

    public function lessThan(self $other): bool
    {
        $this->ensureNotNull();
        $other->ensureNotNull();

        return $this->value < $other->value();
    }

    public function greaterThanOrEqual(self $other): bool
    {
        $this->ensureNotNull();
        $other->ensureNotNull();

        return $this->value >= $other->value();
    }

    public function lessThanOrEqual(self $other): bool
    {
        $this->ensureNotNull();
        $other->ensureNotNull();

        return $this->value <= $other->value();
    }

    public function add(self $other): static
    {
        $this->ensureNotNull();
        $other->ensureNotNull();

        return new static($this->value + $other->value());
    }

    public function subtract(self $other): static
    {
        $this->ensureNotNull();
        $other->ensureNotNull();

        return new static($this->value - $other->value());
    }

    public function multiply(self $other): static
    {
        $this->ensureNotNull();
        $other->ensureNotNull();

        return new static($this->value * $other->value());
    }

    public function divide(self $other): static
    {
        $this->ensureNotNull();
        $other->ensureNotNull();
        $other->ensureNotZero();

        return new static($this->value / $other->value());
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

    abstract function validate(): void;

    public function __toString(): string
    {
        return (string) $this->value;
    }
}