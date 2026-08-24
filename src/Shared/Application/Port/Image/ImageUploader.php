<?php

namespace App\Shared\Application\Port\Image;

interface ImageUploader
{
    public function upload(string $path): string;
}
