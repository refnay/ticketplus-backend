<?php

namespace App\Shared\Domain\Utils;

class PayloadMapper
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function fromData(array $data): self
    {
        return new self($data);
    }

    private function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    public function string(string $key): string
    {
        $value = $this->get($key);

        return is_string($value) ? $value : '';
    }

    public function nullableString(string $key): ?string
    {
        $value = $this->get($key);

        if ($value === null) {
            return null;
        }

        return is_string($value) ? $value : null;
    }

    public function int(string $key): int
    {
        $value = $this->get($key);

        return is_int($value) ? $value : 0;
    }

    public function nullableInt(string $key): ?int
    {
        $value = $this->get($key);

        if ($value === null) {
            return null;
        }

        return is_int($value) ? $value : null;
    }

    public function bool(string $key): bool
    {
        $value = $this->get($key);

        return is_bool($value) ? $value : false;
    }

    public function boolFromString(string $key): bool
    {
        $value = $this->get($key);

        if (!is_string($value)) {
            return false;
        }

        return $this->stringToBool($value);
    }

    public function nullableBool(string $key): ?bool
    {
        $value = $this->get($key);

        if ($value === null) {
            return null;
        }

        return is_bool($value) ? $value : null;
    }

    private function stringToBool(string $value): bool
    {
        $normalized = strtolower(trim($value));

        if (in_array($normalized, ['true', '1', 'yes', 'on'], true)) {
            return true;
        }

        if (in_array($normalized, ['false', '0', 'no', 'off'], true)) {
            return false;
        }

        return false;
    }

    public function float(string $key): float
    {
        $value = $this->get($key);

        if (!is_float($value) && !is_int($value)) {
            return 0.0;
        }

        return (float) $value;
    }

    public function nullableFloat(string $key): ?float
    {
        $value = $this->get($key);

        if ($value === null) {
            return null;
        }

        if (!is_float($value) && !is_int($value)) {
            return null;
        }

        return (float) $value;
    }

    public function array(string $key): array
    {
        $value = $this->get($key);

        return is_array($value) ? $value : [];
    }

    public function nullableArray(string $key): ?array
    {
        $value = $this->get($key);

        if ($value === null) {
            return null;
        }

        return is_array($value) ? $value : null;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }
}