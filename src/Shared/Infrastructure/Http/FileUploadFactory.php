<?php

namespace App\Shared\Infrastructure\Http;

use App\Shared\Application\Input\FileUpload;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FileUploadFactory
{
    public static function fromRequestFile(mixed $file): ?FileUpload
    {
        if (!$file instanceof UploadedFile) {
            return null;
        }

        return new FileUpload($file->getPathname());
    }
}
