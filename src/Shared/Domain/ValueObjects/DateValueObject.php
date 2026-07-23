<?php

namespace App\Shared\Domain\ValueObjects;

use DateTimeImmutable;
use DateTimeInterface;
use App\Shared\Domain\Exceptions\ValueObjectUsedWhileNull;
use DateTime;

abstract class DateValueObject
{
    public function __construct(protected ?DateTimeImmutable $value)
    {
    }

    public static function today(): static
    {
        return new static(new DateTimeImmutable('today'));
    }

    public static function fromString(?string $date): static
    {
        if (is_null($date)) {
            return new static(null);
        }
        
        return new static(DateTimeImmutable::createFromFormat('Y-m-d', $date));
    }

    public static function fromDate(DateTimeInterface $dateTime): static
    {
        return new static(DateTimeImmutable::createFromFormat('Y-m-d', $dateTime->format('Y-m-d')));
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

        return $this->value->format('Y-m-d') === $other->value()->format('Y-m-d');
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

    public function addDays(int $days): static
    {
        $this->ensureNotNull();

        return new static($this->value->modify(sprintf('+%d days', $days)));
    }

    public function subDays(int $days): static
    {
        $this->ensureNotNull();

        return new static($this->value->modify(sprintf('-%d days', $days)));
    }

    public function format(string $format = 'Y-m-d'): string
    {
        $this->ensureNotNull();

        return $this->value->format($format);
    }

    public function toDateTime(): DateTime
    {
        $this->ensureNotNull();

        return DateTime::createFromFormat('Y-m-d', $this->value->format('Y-m-d'));
    }

    public function __toString(): string
    {
        return $this->isNull() ? '' : $this->value->format('Y-m-d');
    }

    public function asDMY(): string
    {
        return $this->isNull() ? '' : $this->value->format('d/m/Y');
    }

    abstract function validate(): void;
}