<?php

namespace App\Shared\Domain\Image;

interface ImageUploader
{
    public function upload(string $path): string;
}
