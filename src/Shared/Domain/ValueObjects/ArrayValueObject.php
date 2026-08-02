<?php

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\ArrayKeyNotFound;
use App\Shared\Domain\Exceptions\ValueObjectUsedWhileNull;

abstract class ArrayValueObject
{
    public function __construct(protected ?array $value)
    {
    }

    public static function fromArray(?array $value): static
    {
        if (is_null($value)) {
            return static::fromNull();
        }

        return new static($value);
    }

    public static function fromNull(): static
    {
        return new static(null);
    }

    public static function fromEmpty(): static
    {
        return new static([]);
    }


    public function value(): ?array
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value();
    }

    public function contains(mixed $item): bool
    {
        $this->ensureNotNull();

        return in_array($item, $this->value, true);
    }

    public function count(): int
    {
        return is_null($this->value) ? 0 : count($this->value);
    }

    public function push(mixed $item): static
    {
        $this->ensureNotNull();

        $newValue = $this->value;
        $newValue[] = $item;

        return new static($newValue);
    }

    public function merge(array $array): static
    {
        $this->ensureNotNull();

        return new static(array_merge($this->value, $array));
    }

    public function map(callable $callback): static
    {
        $this->ensureNotNull();

        return new static(array_map($callback, $this->value));
    }

    public function filter(callable $callback): static
    {
        $this->ensureNotNull();

        return new static(array_filter($this->value, $callback));
    }

    public function isNull(): bool
    {
        return is_null($this->value);
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function hasKey(string|int $key): bool
    {
        return !is_null($this->value) && array_key_exists($key, $this->value);
    }

    abstract function validate(): void;

    public function get(string|int $key): mixed
    {
        $this->ensureNotNull();

        if (!$this->hasKey($key)) {
            throw new ArrayKeyNotFound();
        }

        return $this->value[$key];
    }

    private function ensureNotNull(): void
    {
        if ($this->isNull()) {
            throw new ValueObjectUsedWhileNull();
        }
    }
}