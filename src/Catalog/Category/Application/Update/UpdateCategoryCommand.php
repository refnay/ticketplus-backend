<?php

namespace App\Catalog\Category\Application\Update;

use App\Shared\Application\Command\BaseCommand;
use App\Shared\Domain\Utils\PayloadMapper;

class UpdateCategoryCommand extends BaseCommand
{
    public function __construct(private string $id, private string $name, private int $reference)
    {
    }

    public static function create(string $id, array $data): self
    {
        $payload = PayloadMapper::fromData($data);

        return new self(
            $id,
            $payload->string('name'),
            $payload->int('reference')
        );
    }

    public function id(): string
    {
        return $this->id;
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