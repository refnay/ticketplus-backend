<?php

namespace App\Shared\Infrastructure\Image;

use App\Shared\Domain\Image\ImageUploader;
use RuntimeException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class ImgBBImageUploader implements ImageUploader
{
    public function __construct(
        private HttpClientInterface $client,
        private string $apiKey,
    ) {
    }

    public function upload(string $path): string
    {
        $content = file_get_contents($path);

        if ($content === false) {
            throw new RuntimeException(sprintf('Unable to read image "%s".', $path));
        }

        $image = base64_encode($content);

        $response = $this->client->request('POST', 'https://api.imgbb.com/1/upload', [
            'query' => ['key' => $this->apiKey],
            'body' => ['image' => $image],
        ]);

        $json = $response->toArray();

        if (!isset($json['success']) || !$json['success']) {
            throw new RuntimeException('Error al subir imagen a ImgBB.');
        }

        return $json['data']['url'];
    }
}
