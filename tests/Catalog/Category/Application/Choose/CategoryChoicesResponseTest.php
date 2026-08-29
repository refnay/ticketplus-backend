<?php

namespace App\Tests\Catalog\Category\Application\Choose;

use App\Catalog\Category\Application\Choose\CategoryChoicesResponse;
use PHPUnit\Framework\TestCase;

final class CategoryChoicesResponseTest extends TestCase
{
    public function testItSerializesChoicesAsCodeAndLabel(): void
    {
        $response = new CategoryChoicesResponse(
            ['code' => 'category-1', 'label' => 'Conciertos'],
            ['code' => 'category-2', 'label' => 'Teatro'],
        );

        self::assertSame([
            ['code' => 'category-1', 'label' => 'Conciertos'],
            ['code' => 'category-2', 'label' => 'Teatro'],
        ], $response->jsonSerialize());
    }
}
