<?php

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\ValueObjectUsedWhileNull;

abstract class BooleanValueObject
{
    public function __construct(protected ?bool $value)
    {
        $this->validate();
    }

    public static function fromBool(?bool $value): static
    {
        return new static($value);
    }

    public static function enable(): static
    {
        return new static(true);
    }

    public function enabled(): void
    {
        $this->value = true;
    }

    public static function disable(): static
    {
        return new static(false);
    }

    public function disabled(): void
    {
        $this->value = false;
    }

    public static function fromNull(): static
    {
        return new static(null);
    }

    public function value(): ?bool
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value();
    }

    public function isNull(): bool
    {
        return is_null($this->value);
    }

    public function isEnable(): bool
    {
        $this->ensureNotNull();

        return $this->value === true;
    }

    public function isDisable(): bool
    {
        $this->ensureNotNull();

        return $this->value === false;
    }

    private function ensureNotNull(): void
    {
        if ($this->value === null) {
            throw new ValueObjectUsedWhileNull();
        }
    }

    public function __toString(): string
    {
        return $this->value === null
            ? ''
            : ($this->value ? 'true' : 'false');
    }

    abstract protected function validate(): void;
}