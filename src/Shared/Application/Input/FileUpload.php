<?php

namespace App\Shared\Application\Input;

final readonly class FileUpload
{
    public function __construct(private string $path)
    {
    }

    public function path(): string
    {
        return $this->path;
    }
}
