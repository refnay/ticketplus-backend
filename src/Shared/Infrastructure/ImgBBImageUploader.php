<?php

namespace App\Shared\Infrastructure;

use App\Shared\Domain\Services\ImageUploader;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImgBBImageUploader implements ImageUploader
{
    private string $apiKey;

    public function __construct(private readonly HttpClientInterface $client)
    {
        $this->apiKey = $_ENV['IMGBB_API_KEY'];
    }

    public function upload(string $path): string
    {
        $image = base64_encode(file_get_contents($path));

        $response = $this->client->request('POST', 'https://api.imgbb.com/1/upload', [
            'query' => ['key' => $this->apiKey ],
            'body' => ['image' => $image],
        ]);

        $json = $response->toArray();

        if (!isset($json['success']) || !$json['success']) {
            throw new \RuntimeException('Error al subir imagen a ImgBB.');
        }

        return $json['data']['url'];
    }
}
