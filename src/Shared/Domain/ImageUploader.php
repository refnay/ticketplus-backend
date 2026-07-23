<?php

namespace App\Shared\Domain;

interface ImageUploader
{
    public function upload(string $path): string;
}
