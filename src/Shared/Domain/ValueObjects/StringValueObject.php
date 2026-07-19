<?php

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\ValueObjectUsedWhileNull;

abstract class StringValueObject
{
    public function __construct(protected ?string $value)
    {
    }

    public static function fromString(?string $value): static
    {
        if (is_null($value)) {
            return static::fromNull();
        }

        return new static(trim($value));
    }

    public static function fromNull(): static
    {
        return new static(null);
    }

    public static function fromEmpty(): static
    {
        return new static('');
    }

    public function value(): ?string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value();
    }

    public function length(): int
    {
        return $this->isNull() ? 0 : mb_strlen($this->value);
    }

    public function toUpper(): static
    {
        $this->ensureNotNull();
        
        return new static(mb_strtoupper($this->value));
    }

    public function toLower(): static
    {
        $this->ensureNotNull();

        return new static(mb_strtolower($this->value));
    }

    public function trim(): static
    {
        $this->ensureNotNull();

        return new static(trim($this->value));
    }

    public function contains(string $needle): bool
    {
        $this->ensureNotNull();
        
        return mb_strpos($this->value, $needle) !== false;
    }

    public function isNull(): bool
    {
        return is_null($this->value);
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function isMissing(): bool
    {
        return $this->isNull() || $this->isEmpty();
    }

    private function ensureNotNull(): void
    {
        if ($this->isNull()) {
            throw new ValueObjectUsedWhileNull();
        }
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    abstract function validate(): void;
}