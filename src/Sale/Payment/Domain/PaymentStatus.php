<?php

namespace App\Sale\Payment\Domain;

use App\Shared\Domain\ValueObjects\IntValueObject;
use Override;

class PaymentStatus extends IntValueObject
{
    #[Override]
    public function validate(): void
    {
    }

    public static function pending(): self
    {
        return new self(PaymentStatusList::PENDING->value);
    }

    public static function processing(): self
    {
        return new self(PaymentStatusList::PROCESSING->value);
    }

    public static function declined(): self
    {
        return new self(PaymentStatusList::DECLINED->value);
    }

    public function isProcessing(): bool
    {
        return PaymentStatusList::PROCESSING->value === $this->value;;
    }

    public function isApproved(): bool
    {
        return PaymentStatusList::APPROVED->value === $this->value;;
    }
}
