<?php

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\ArrayKeyNotFound;

abstract class ArrayValueObject
{
    public function __construct(protected array $value)
    {
    }

    public static function fromArray(array $value): static
    {
        return new static($value);
    }

    public static function fromEmpty(): static
    {
        return new static([]);
    }

    public function value(): array
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value();
    }

    public function contains(mixed $item): bool
    {
        return in_array($item, $this->value, true);
    }

    public function count(): int
    {
        return count($this->value);
    }

    public function push(mixed $item): static
    {
        $newValue = $this->value;
        $newValue[] = $item;

        return new static($newValue);
    }

    public function merge(array $array): static
    {
        return new static(array_merge($this->value, $array));
    }

    public function map(callable $callback): static
    {
        return new static(array_map($callback, $this->value));
    }

    public function filter(callable $callback): static
    {
        return new static(array_filter($this->value, $callback));
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function hasKey(string|int $key): bool
    {
        return array_key_exists($key, $this->value);
    }

    abstract function validate(): void;

    public function get(string|int $key): mixed
    {
        if (!$this->hasKey($key)) {
            throw new ArrayKeyNotFound();
        }
        
        return $this->value[$key];
    }
}