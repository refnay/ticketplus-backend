<?php

namespace App\Shared\Infrastructure\Http;

use App\Shared\Application\Input\FileUpload;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FileBuilder
{
    public static function fromRequest(mixed $file): ?FileUpload
    {
        if (!$file instanceof UploadedFile) {
            return null;
        }

        return new FileUpload($file->getPathname());
    }
}
