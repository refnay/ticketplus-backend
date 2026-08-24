<?php

namespace App\Tests\Infrastructure\Messenger;

use App\Shared\Infrastructure\Messenger\SymfonyCommandBus;
use App\Shared\Infrastructure\Messenger\SymfonyEventBus;
use App\Shared\Infrastructure\Messenger\SymfonyQueryBus;
use LogicException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class SymfonyBusTest extends TestCase
{
    public function testCommandBusReturnsTheHandlerResult(): void
    {
        $command = new \stdClass();
        $messenger = $this->createMock(MessageBusInterface::class);
        $messenger->expects(self::once())
            ->method('dispatch')
            ->with($command)
            ->willReturn(new Envelope($command, [new HandledStamp('command-result', 'handler')]));

        self::assertSame('command-result', (new SymfonyCommandBus($messenger))->dispatch($command));
    }

    public function testQueryBusReturnsTheHandlerResult(): void
    {
        $query = new \stdClass();
        $messenger = $this->createMock(MessageBusInterface::class);
        $messenger->expects(self::once())
            ->method('dispatch')
            ->with($query)
            ->willReturn(new Envelope($query, [new HandledStamp('query-result', 'handler')]));

        self::assertSame('query-result', (new SymfonyQueryBus($messenger))->ask($query));
    }

    public function testCommandBusRejectsAnUnhandledCommand(): void
    {
        $command = new \stdClass();
        $messenger = $this->createStub(MessageBusInterface::class);
        $messenger->method('dispatch')->willReturn(new Envelope($command));

        $this->expectException(LogicException::class);
        (new SymfonyCommandBus($messenger))->dispatch($command);
    }

    public function testQueryBusRejectsAnUnhandledQuery(): void
    {
        $query = new \stdClass();
        $messenger = $this->createStub(MessageBusInterface::class);
        $messenger->method('dispatch')->willReturn(new Envelope($query));

        $this->expectException(LogicException::class);
        (new SymfonyQueryBus($messenger))->ask($query);
    }

    public function testEventBusPublishesEveryEvent(): void
    {
        $first = new \stdClass();
        $second = new \stdClass();
        $messenger = $this->createMock(MessageBusInterface::class);
        $messenger->expects(self::exactly(2))
            ->method('dispatch')
            ->willReturnCallback(static fn (object $event): Envelope => new Envelope($event));

        (new SymfonyEventBus($messenger))->publish($first, $second);
    }
}
