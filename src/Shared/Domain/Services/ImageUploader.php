<?php

namespace App\Shared\Domain\Services;

interface ImageUploader
{
    public function upload(string $path): string;
}
