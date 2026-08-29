<?php

namespace App\Tests\Catalog\Category\Application\Choose;

use App\Catalog\Category\Application\Choose\CategoryChooser;
use App\Catalog\Category\Domain\Category;
use App\Catalog\Category\Domain\CategoryId;
use App\Catalog\Category\Domain\CategoryName;
use App\Catalog\Category\Domain\CategoryReference;
use App\Catalog\Category\Domain\CategoryRepository;
use App\Catalog\Shared\Domain\CompanyId;
use PHPUnit\Framework\TestCase;

final class CategoryChooserTest extends TestCase
{
    public function testItReturnsCompanyCategoriesAsChoices(): void
    {
        $companyId = '018f7c54-5f88-7e04-8a90-7af68c932555';
        $repository = $this->createMock(CategoryRepository::class);
        $repository->expects(self::once())
            ->method('searchByFilters')
            ->with(['company' => $companyId], 'name', 'ASC', null, null)
            ->willReturn([
                new Category(
                    CategoryId::fromString('018f7c54-5f88-7e04-8a90-7af68c932551'),
                    CategoryName::fromString('Conciertos'),
                    CategoryReference::fromInt(1),
                    CompanyId::fromString($companyId),
                ),
            ]);

        $response = (new CategoryChooser($repository))->__invoke($companyId);

        self::assertSame([
            [
                'code' => '018f7c54-5f88-7e04-8a90-7af68c932551',
                'label' => 'Conciertos',
            ],
        ], $response->jsonSerialize());
    }
}
