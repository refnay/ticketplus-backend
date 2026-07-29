<?php

namespace App\Shared\Domain\ValueObjects;

use DateTimeImmutable;
use DateTimeInterface;
use DateTime;
use App\Shared\Domain\Exceptions\ValueObjectUsedWhileNull;

abstract class TimeValueObject
{
    public function __construct(protected ?DateTimeImmutable $value)
    {
    }

    public static function now(): static
    {
        return new static(new DateTimeImmutable());
    }

    public static function fromString(?string $time): static
    {
        if (is_null($time)) {
            return new static(null);
        }

        $dateTime = DateTimeImmutable::createFromFormat('H:i:s', $time);

        return new static($dateTime ?: null);
    }

    public static function fromDateTime(DateTimeInterface $dateTime): static
    {
        return new static(DateTimeImmutable::createFromFormat('H:i:s', $dateTime->format('H:i:s')));
    }

    public static function fromNull(): static
    {
        return new static(null);
    }

    public function value(): ?DateTimeImmutable
    {
        return $this->value;
    }

    public function isNull(): bool
    {
        return is_null($this->value);
    }

    private function ensureNotNull(): void
    {
        if ($this->isNull()) {
            throw new ValueObjectUsedWhileNull();
        }
    }

    public function equals(self $other): bool
    {
        $this->ensureNotNull();
        $other->ensureNotNull();

        return $this->value->format('H:i:s') === $other->value()->format('H:i:s');
    }

    public function before(self $other): bool
    {
        $this->ensureNotNull();
        $other->ensureNotNull();

        return $this->value < $other->value();
    }

    public function after(self $other): bool
    {
        $this->ensureNotNull();
        $other->ensureNotNull();

        return $this->value > $other->value();
    }

    public function addMinutes(int $minutes): static
    {
        $this->ensureNotNull();

        return new static($this->value->modify(sprintf('+%d minutes', $minutes)));
    }

    public function subMinutes(int $minutes): static
    {
        $this->ensureNotNull();

        return new static($this->value->modify(sprintf('-%d minutes', $minutes)));
    }

    public function format(string $format = 'H:i:s'): string
    {
        $this->ensureNotNull();

        return $this->value->format($format);
    }

    public function toDateTime(): DateTime
    {
        $this->ensureNotNull();

        return DateTime::createFromFormat('H:i:s', $this->value->format('H:i:s'));
    }

    public function __toString(): string
    {
        return $this->isNull() ? '' : $this->value->format('H:i:s');
    }

    public function asHM(): string
    {
        $this->ensureNotNull();

        return $this->value->format('H:i');
    }

    abstract function validate(): void;
}
