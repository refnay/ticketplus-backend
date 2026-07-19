<?php

namespace App\Shared\Domain\ValueObjects;

use Symfony\Component\Uid\Uuid;

abstract class UuidValueObject
{
    public function __construct(protected ?string $value)
    {
    }

    public static function generate(): static
    {
        return new static(Uuid::v4()->toRfc4122());
    }

    public static function fromString(?string $value): static
    {
        return new static($value);
    }

    public static function fromNull(): static
    {
        return new static(null);
    }

    public function value(): ?string
    {
        return $this->value;
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

    public function equals(self $other): bool
    {
        if ($this->isNull() || $other->isNull()) {
            return false;
        }

        return $this->value === $other->value();
    }

    public function toUuid(): Uuid
    {
        return Uuid::fromString($this->value);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    abstract function validate(): void;
}
