<?php

namespace App\Catalog\Category\Application\Create;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;

class CreateCategoryCommand extends BaseCommand
{
    public function __construct(private string $name, private int $reference)
    {
    }

    public static function create(array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $payload->string('name'),
            $payload->int('reference')
        );
    }

    public function name(): string
    {
        return $this->name;
    }

    public function reference(): int
    {
        return $this->reference;
    }
}