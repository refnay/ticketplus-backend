<?php

namespace App\Shared\Domain\ValueObjects;

use DateTimeImmutable;
use DateTimeInterface;
use DateInterval;
use App\Shared\Domain\Exceptions\ValueObjectUsedWhileNull;
use DateTime;

abstract class DateTimeValueObject
{
    public function __construct(protected ?DateTimeImmutable $value)
    {
    }

    public static function now(): static
    {
        return new static(new DateTimeImmutable());
    }

    public static function fromString(?string $dateTime): static
    {
        if (is_null($dateTime)) {
            return new static(null);
        }
        
        return new static(DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, $dateTime));
    }

    public static function fromDateTime(?DateTimeInterface $dateTime): static
    {
        if (is_null($dateTime)) {
            return new static(null);
        }
         
        $immutable = $dateTime instanceof DateTimeImmutable
            ? $dateTime
            : DateTimeImmutable::createFromMutable($dateTime);

        return new static($immutable);
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

        return $this->value == $other->value();
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

    public function add(DateInterval $interval): static
    {
        $this->ensureNotNull();

        return new static($this->value->add($interval));
    }

    public function sub(DateInterval $interval): static
    {
        $this->ensureNotNull();

        return new static($this->value->sub($interval));
    }

    public function format(string $format = DateTimeInterface::ATOM): string
    {
        $this->ensureNotNull();
        
        return $this->value->format($format);
    }

    public function toDateTime(): DateTime
    {
        $this->ensureNotNull();

        return new DateTime($this->value->format(DateTimeInterface::ATOM));
    }

    public function __toString(): string
    {
        return $this->isNull() ? '' : $this->value->format(DateTimeInterface::ATOM);
    }

    abstract function validate(): void;
}