<?php

namespace App\Shared\Infrastructure\Pdf\Storage;

use App\Shared\Application\Port\Pdf\PdfStorage;
use RuntimeException;

final class LocalPdfStorage implements PdfStorage
{
    public function save(string $content, string $path, string $filename): string
    {
        $directory = rtrim($path, DIRECTORY_SEPARATOR);

        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true) && !is_dir($directory)) {
                throw new RuntimeException(
                    sprintf(
                        'Unable to create directory "%s".',
                        $directory
                    )
                );
            }
        }

        $filepath = $directory . DIRECTORY_SEPARATOR . $filename;

        if (file_put_contents($filepath, $content) === false) {
            throw new RuntimeException(sprintf('Unable to save PDF "%s".', $filepath));
        }

        return $filepath;
    }
}
