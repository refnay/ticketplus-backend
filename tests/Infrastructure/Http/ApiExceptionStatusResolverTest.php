<?php

namespace App\Tests\Infrastructure\Http;

use App\Catalog\Category\Domain\Exceptions\CategoryNotFound;
use App\Catalog\Event\Domain\Exceptions\EventAlreadyExists;
use App\Sale\Order\Domain\Exceptions\OrderNotCancelable;
use App\Shared\Application\Security\Exception\UserNotAllowed;
use App\Shared\Infrastructure\Http\ApiExceptionStatusResolver;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class ApiExceptionStatusResolverTest extends TestCase
{
    #[DataProvider('exceptions')]
    public function testItMapsExpectedApplicationAndDomainExceptions(Throwable $exception, int $status): void
    {
        self::assertSame($status, (new ApiExceptionStatusResolver())->resolve($exception));
    }

    public function testItIgnoresUnexpectedTechnicalExceptions(): void
    {
        self::assertNull((new ApiExceptionStatusResolver())->resolve(new LogicException('failure')));
    }

    public static function exceptions(): iterable
    {
        yield 'authorization' => [new UserNotAllowed(), Response::HTTP_FORBIDDEN];
        yield 'not found' => [new CategoryNotFound(), Response::HTTP_NOT_FOUND];
        yield 'duplicate' => [new EventAlreadyExists(), Response::HTTP_CONFLICT];
        yield 'invalid domain state' => [new OrderNotCancelable(), Response::HTTP_UNPROCESSABLE_ENTITY];
    }
}
