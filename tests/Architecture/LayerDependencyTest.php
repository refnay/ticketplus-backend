<?php

namespace App\Tests\Architecture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final class LayerDependencyTest extends TestCase
{
    #[DataProvider('domainFiles')]
    public function testDomainDoesNotDependOnOuterLayers(string $file): void
    {
        $source = file_get_contents($file);

        self::assertIsString($source);
        self::assertStringNotContainsString('\\Application\\', $source, $file);
        self::assertStringNotContainsString('\\Infrastructure\\', $source, $file);
    }

    #[DataProvider('applicationFiles')]
    public function testApplicationDoesNotDependOnInfrastructure(string $file): void
    {
        $source = file_get_contents($file);

        self::assertIsString($source);
        self::assertStringNotContainsString('\\Infrastructure\\', $source, $file);
        self::assertDoesNotMatchRegularExpression(
            '/^use (Symfony|Doctrine|MercadoPago|Dompdf|Endroid)\\\\/m',
            $source,
            $file,
        );
    }

    public static function domainFiles(): iterable
    {
        yield from self::filesInLayer('Domain');
    }

    public static function applicationFiles(): iterable
    {
        yield from self::filesInLayer('Application');
    }

    private static function filesInLayer(string $layer): iterable
    {
        $sourceDirectory = dirname(__DIR__, 2).'/src';
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceDirectory));

        foreach ($iterator as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $path = str_replace('\\', '/', $file->getPathname());
            if (str_contains($path, '/'.$layer.'/')) {
                yield $path => [$file->getPathname()];
            }
        }
    }
}
